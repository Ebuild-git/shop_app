<?php

namespace App\Services;

use App\Events\AdminEvent;
use App\Models\notifications;
use App\Models\OrdersItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Services\AramexService;
use App\Services\Concerns\NotifiesPickupCancellation;

class VoyageModeAlertService
{
    use NotifiesPickupCancellation;

    /**
     * update_codes indicating the shipment is already picked up / in a terminal
     * transit state — these don't need an admin heads-up since Aramex already
     * has physical custody of the parcel.
     */
    private const TERMINAL_CODES = ['SH012', 'SH314', 'SH308', 'SH312'];

    /**
     * When a user activates voyage mode, attempt to auto-cancel any active
     * Aramex pickups tied to them (as vendor or buyer). Successes and
     * failures are both reported to admin, and the actual buyer/seller are
     * notified for each pickup that gets cancelled.
     *
     * @param User $user The user activating voyage mode
     */
    public function handleVoyageModeActivated(User $user): void
    {
        $items = OrdersItem::where(function ($query) use ($user) {
                $query->where('vendor_id', $user->id)
                      ->orWhereHas('order', function ($q) use ($user) {
                          $q->where('buyer_id', $user->id);
                      });
            })
            ->whereNotNull('pickup_guid')
            ->with('latestShipmentHistory', 'order.buyer', 'post', 'vendor')
            ->get();

        if ($items->isEmpty()) {
            return;
        }

        $aramex = new AramexService();
        $now = Carbon::now()->format('Y-m-d H:i');
        $userCode = 'U' . (1000 + $user->id);
        $pendingItems = collect();
        $cancelledItems = collect();

        // Group by pickup_guid since cancelling is per-pickup, not per-item
        foreach ($items->groupBy('pickup_guid') as $pickupGuid => $groupedItems) {
            $latestCode = $groupedItems->first()->latestShipmentHistory?->update_code;
            $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

            if ($isTerminal) {
                continue;
            }

            $comment = "Pickup supprimé automatiquement - {$userCode} ({$user->username}) en mode voyage";

            $response = $aramex->cancelPickup($pickupGuid, $comment);
            $hasErrors = $response['HasErrors'] ?? true;
            $msg = collect($response['Notifications'] ?? [])->pluck('Message')->implode('; ');
            $alreadyCancelled = str_contains(strtolower($msg), 'cannot cancel a cancelled pickup');

            if ($hasErrors && !$alreadyCancelled) {
                foreach ($groupedItems as $item) {
                    $item->info_auto = "[{$now}] Utilisateur #{$userCode} en mode voyage – Ramassage déjà en cours – à traiter manuellement";
                    $item->save();
                    $pendingItems->push($item);
                }
                continue;
            }

            // Capture these ONCE per pickup group, before local fields get wiped below
            $shipmentId = $groupedItems->first()->shipment_id;
            $order      = $groupedItems->first()->order;
            $vendor     = $groupedItems->first()->vendor;

            foreach ($groupedItems as $item) {
                $itemShipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;
                $formattedShipmentId = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1-$2-$3-$4', $itemShipmentId);
                $role = $item->vendor_id === $user->id ? 'Vendeur' : 'Acheteur';
                $vendorCode = 'U' . (1000 + $item->vendor_id);

                $item->cancelled_pickup_id   = $item->pickup_id;
                $item->cancelled_pickup_guid = $item->pickup_guid;
                $item->cancelled_shipment_id = $item->shipment_id;
                $item->pickup_cancelled_at   = now();

                $item->pickup_id   = null;
                $item->pickup_guid = null;
                $item->shipment_id = null;
                $item->status      = 'pending';
                // $item->info_auto   = "[{$now}] Pickup annulé automatiquement.\nRaison : Mode voyage activé (vendeur {$vendorCode}).\nID expédition : {$itemShipmentId}.";
                $item->info_auto   = "[{$now}] Pickup annulé automatiquement.\nRaison : {$role} « {$user->username} » en voyage.\nID expédition : {$formattedShipmentId}.";
                $item->save();

                if ($item->post) {
                    $item->post->statut      = 'vente';
                    $item->post->sell_at     = null;
                    $item->post->id_user_buy = null;
                    $item->post->save();
                }

                $cancelledItems->push($item);
            }

            // Notify the real buyer/seller once per pickup group, not once per item
            if ($order && $vendor) {
                $this->notifySellerPickupCancelled($vendor, $order, $shipmentId, $groupedItems);
                $this->notifyBuyerPickupCancelled($order, $shipmentId, $groupedItems);
            }

            \Log::info('VoyageModeAlertService: pickup auto-cancelled', [
                'user_id'     => $user->id,
                'pickup_guid' => $pickupGuid,
                'items'       => $groupedItems->pluck('id')->all(),
            ]);
        }

        if ($pendingItems->isNotEmpty()) {
            $this->notifyAdmins($user, $pendingItems); // manual follow-up needed
        }

        if ($cancelledItems->isNotEmpty()) {
            $this->notifyAdminsCancelled($user, $cancelledItems); // FYI, auto-handled
        }
    }

    /**
     * Create an admin-facing notification + broadcast event listing the affected order items.
     */
    private function notifyAdmins(User $user, $pendingItems): void
    {
        $userCode = 'U' . (1000 + $user->id);

        $itemsList = $pendingItems
            ->map(fn($item) => 'CMD-' . ($item->order_id ?? '?') . ' / P' . $item->post_id)
            ->implode(', ');

        $count = $pendingItems->count();

        $notification = new notifications();
        $notification->titre = $user->username . ' a activé le mode voyage avec ' . $count . ' ramassage(s) en attente';
        $notification->id_user = $user->id;
        $notification->type = 'voyage_mode_pending_pickup';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = "L'utilisateur {$userCode} ({$user->username}) a activé le mode voyage "
            . 'alors que ' . $count . ' article(s) attendent toujours un ramassage Aramex : ' . $itemsList . '.';
        $notification->save();

        event(new AdminEvent($user->username . ' a activé le mode voyage avec des ramassages en attente.'));

        \Log::info("VoyageModeAlertService: admin notified", [
            'user_id' => $user->id,
            'items_count' => $count,
        ]);
    }

    /**
     * Create an admin-facing notification for pickups that were successfully auto-cancelled.
     */
    private function notifyAdminsCancelled(User $user, $cancelledItems): void
    {
        $userCode = 'U' . (1000 + $user->id);

        $itemsList = $cancelledItems
            ->map(fn($item) => 'CMD-' . ($item->order_id ?? '?') . ' / P' . $item->post_id)
            ->implode(', ');

        $count = $cancelledItems->count();

        $notification = new notifications();
        $notification->titre = $user->username . ' a activé le mode voyage – ' . $count . ' pickup(s) annulé(s) automatiquement';
        $notification->id_user = $user->id;
        $notification->type = 'voyage_mode_pickup_auto_cancelled';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = "L'utilisateur {$userCode} ({$user->username}) a activé le mode voyage. "
            . $count . ' pickup(s) Aramex ont été annulés automatiquement : ' . $itemsList . '.';
        $notification->save();

        event(new AdminEvent($user->username . ' a activé le mode voyage – pickups annulés automatiquement.'));

        \Log::info("VoyageModeAlertService: admin notified of auto-cancellations", [
            'user_id' => $user->id,
            'items_count' => $count,
        ]);
    }
}
