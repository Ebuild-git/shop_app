<?php

namespace App\Services;

use App\Events\AdminEvent;
use App\Models\notifications;
use App\Models\OrdersItem;
use App\Models\ShipmentStatusHistory;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Services\Concerns\NotifiesPickupCancellation;
use App\Services\AramexService;

class UserDeletionService
{
    use NotifiesPickupCancellation;

    private const TERMINAL_CODES = ['SH012', 'SH314', 'SH308', 'SH312'];

    /**
     * Cancel any active Aramex pickups for order items linked to a deleted user
     * and write an audit note in `info_auto`. Notifies the other party
     * (buyer or seller) when a pickup they're involved in gets cancelled —
     * but never notifies the user being deleted. Admin is notified of both
     * successful auto-cancellations and any that need manual follow-up.
     *
     * @param User $user The user being deleted
     */
    public function handlePickupCancellations(User $user): void
    {
        $sellerItems = OrdersItem::where('vendor_id', $user->id)
            ->whereNotNull('pickup_guid')
            ->get();

        $buyerItems = OrdersItem::whereHas('order', fn($q) => $q->where('buyer_id', $user->id))
            ->whereNotNull('pickup_guid')
            ->get();

        $items = $sellerItems->merge($buyerItems)->unique('id')
            ->load('order.buyer', 'vendor', 'post');

        if ($items->isEmpty()) {
            return;
        }

        $aramex = app(AramexService::class);
        $now = Carbon::now()->format('Y-m-d H:i');
        $userCode = 'U' . (1000 + $user->id);
        $cancelledItems = collect();
        $keptItems = collect();

        foreach ($items->groupBy('pickup_guid') as $pickupGuid => $groupedItems) {
            $latestHistory = ShipmentStatusHistory::where('order_item_id', $groupedItems->first()->id)
                ->orderByDesc('id')
                ->first();

            $latestCode = $latestHistory?->update_code;
            $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

            if (!$isTerminal) {
                $response = $aramex->cancelPickup(
                    $pickupGuid,
                    "Pickup supprimé automatiquement - {$userCode} ({$user->username}) supprimé"
                );

                $hasErrors = $response['HasErrors'] ?? true;
                $msg = collect($response['Notifications'] ?? [])->pluck('Message')->implode('; ');
                $alreadyCancelled = str_contains(strtolower($msg), 'cannot cancel a cancelled pickup');

                if ($hasErrors && !$alreadyCancelled) {
                    \Log::warning("UserDeletionService: Aramex cancelPickup failed for pickup {$pickupGuid}", [
                        'user_id' => $user->id,
                        'message' => $msg,
                    ]);
                }

                // Capture these ONCE per pickup group, before local fields get wiped below
                $shipmentId = $groupedItems->first()->shipment_id;
                $order      = $groupedItems->first()->order;
                $vendor     = $groupedItems->first()->vendor;

                foreach ($groupedItems as $item) {
                    $itemShipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;
                    $formattedShipmentId = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1-$2-$3-$4', $itemShipmentId);
                    $role = $item->vendor_id === $user->id ? 'Vendeur' : 'Acheteur';

                    $item->cancelled_pickup_id   = $item->pickup_id;
                    $item->cancelled_pickup_guid = $item->pickup_guid;
                    $item->cancelled_shipment_id = $item->shipment_id;
                    $item->pickup_cancelled_at   = now();

                    $item->pickup_id   = null;
                    $item->pickup_guid = null;
                    $item->shipment_id = null;
                    $item->status      = 'pending';
                    $item->info_auto   = "[{$now}] Pickup annulé automatiquement.\nRaison : {$role} « {$user->username} » supprimé.\nID expédition : {$formattedShipmentId}.";
                    $item->save();

                    if ($item->post) {
                        $item->post->statut      = 'vente';
                        $item->post->sell_at     = null;
                        $item->post->id_user_buy = null;
                        $item->post->save();
                    }

                    $cancelledItems->push($item);
                }

                // Notify once per pickup group — and never notify the user being deleted
                if ($order && $vendor) {
                    if ($vendor->id !== $user->id) {
                        $this->notifySellerPickupCancelled($vendor, $order, $shipmentId, $groupedItems);
                    }
                    if ($order->buyer && $order->buyer->id !== $user->id) {
                        $this->notifyBuyerPickupCancelled($order, $shipmentId, $groupedItems);
                    }
                }
            } else {
                foreach ($groupedItems as $item) {
                    $shipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;
                    $formattedShipmentId = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1-$2-$3-$4', $shipmentId);
                    $role = $item->vendor_id === $user->id ? 'Vendeur' : 'Acheteur';

                    $item->info_auto = "[{$now}] Ramassage conservé, non annulé.\nRaison : {$role} « {$user->username} » supprimé, expédition déjà en transit (code : {$latestCode}).\nID expédition : {$formattedShipmentId}.";
                    $item->save();
                    $keptItems->push($item);
                }
            }

            \Log::info("UserDeletionService: processed pickup {$pickupGuid}", [
                'user_id'  => $user->id,
                'terminal' => $isTerminal,
                'items'    => $groupedItems->pluck('id')->all(),
            ]);
        }

        if ($cancelledItems->isNotEmpty()) {
            $this->notifyAdminsUserDeletedCancelled($user, $cancelledItems);
        }

        if ($keptItems->isNotEmpty()) {
            $this->notifyAdminsUserDeletedKept($user, $keptItems);
        }
    }

    /**
     * Notify admin that pickups were successfully auto-cancelled following account deletion.
     */
    private function notifyAdminsUserDeletedCancelled(User $user, $cancelledItems): void
    {
        $userCode = 'U' . (1000 + $user->id);

        $itemsList = $cancelledItems
            ->map(fn($item) => 'CMD-' . ($item->order_id ?? '?') . ' / P' . $item->post_id)
            ->implode(', ');

        $count = $cancelledItems->count();

        $notification = new notifications();
        $notification->titre = $user->username . ' supprimé – ' . $count . ' pickup(s) annulé(s) automatiquement';
        $notification->id_user = $user->id;
        $notification->type = 'user_deleted_pickup_auto_cancelled';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = "L'utilisateur {$userCode} ({$user->username}) a été supprimé. "
            . $count . ' pickup(s) Aramex ont été annulés automatiquement : ' . $itemsList . '.';
        $notification->save();

        event(new AdminEvent($user->username . ' supprimé – pickups annulés automatiquement.'));

        \Log::info("UserDeletionService: admin notified of auto-cancellations", [
            'user_id' => $user->id,
            'items_count' => $count,
        ]);
    }

    /**
     * Notify admin that some pickups could NOT be cancelled (already in transit)
     * following account deletion, and need manual follow-up.
     */
    private function notifyAdminsUserDeletedKept(User $user, $keptItems): void
    {
        $userCode = 'U' . (1000 + $user->id);

        $itemsList = $keptItems
            ->map(fn($item) => 'CMD-' . ($item->order_id ?? '?') . ' / P' . $item->post_id)
            ->implode(', ');

        $count = $keptItems->count();

        $notification = new notifications();
        $notification->titre = $user->username . ' supprimé – ' . $count . ' ramassage(s) conservé(s), à traiter manuellement';
        $notification->id_user = $user->id;
        $notification->type = 'user_deleted_pickup_kept';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = "L'utilisateur {$userCode} ({$user->username}) a été supprimé, mais "
            . $count . ' ramassage(s) étaient déjà en transit et n\'ont pas pu être annulés : ' . $itemsList . '.';
        $notification->save();

        event(new AdminEvent($user->username . ' supprimé – ramassages conservés à traiter manuellement.'));

        \Log::info("UserDeletionService: admin notified of kept pickups", [
            'user_id' => $user->id,
            'items_count' => $count,
        ]);
    }
}
