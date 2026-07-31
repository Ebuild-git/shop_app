<?php

namespace App\Services;

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
     * but never notifies the user being deleted.
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
        $userLabel = "utilisateur #{$user->id} ({$user->username})";

        foreach ($items->groupBy('pickup_guid') as $pickupGuid => $groupedItems) {
            $latestHistory = ShipmentStatusHistory::where('order_item_id', $groupedItems->first()->id)
                ->orderByDesc('id')
                ->first();

            $latestCode = $latestHistory?->update_code;
            $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

            if (!$isTerminal) {
                $response = $aramex->cancelPickup(
                    $pickupGuid,
                    "Pickup supprimé automatiquement - {$userLabel} supprimé"
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

                    $item->cancelled_pickup_id   = $item->pickup_id;
                    $item->cancelled_pickup_guid = $item->pickup_guid;
                    $item->cancelled_shipment_id = $item->shipment_id;
                    $item->pickup_cancelled_at   = now();

                    $item->pickup_id   = null;
                    $item->pickup_guid = null;
                    $item->shipment_id = null;
                    $item->status      = 'pending';
                    $item->info_auto = "[{$now}] {$userLabel} supprimé – Expédition {$itemShipmentId} – Pickup annulé automatiquement";
                    $item->save();

                    if ($item->post) {
                        $item->post->statut      = 'vente';
                        $item->post->sell_at     = null;
                        $item->post->id_user_buy = null;
                        $item->post->save();
                    }
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
                    $item->info_auto = "[{$now}] {$userLabel} supprimé – Expédition {$shipmentId} – Ramassage conservé (code: {$latestCode}) – à traiter manuellement";
                    $item->save();
                }
            }

            \Log::info("UserDeletionService: processed pickup {$pickupGuid}", [
                'user_id'  => $user->id,
                'terminal' => $isTerminal,
                'items'    => $groupedItems->pluck('id')->all(),
            ]);
        }
    }
}
