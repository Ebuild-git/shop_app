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

    // public function handlePickupCancellations(User $user): void
    // {
    //     $sellerItems = OrdersItem::where('vendor_id', $user->id)
    //         ->whereNotNull('pickup_guid')
    //         ->get();

    //     $buyerItems = OrdersItem::whereHas('order', fn($q) => $q->where('buyer_id', $user->id))
    //         ->whereNotNull('pickup_guid')
    //         ->get();

    //     $items = $sellerItems->merge($buyerItems)->unique('id')
    //         ->load('order.buyer', 'vendor', 'post');

    //     if ($items->isEmpty()) {
    //         return;
    //     }

    //     $aramex = app(AramexService::class);
    //     $now = Carbon::now()->format('Y-m-d H:i');
    //     $userCode = 'U' . (1000 + $user->id);
    //     $displayName = $user->username_deleted ?? $user->username; // fallback if not yet anonymized
    //     $cancelledItems = collect();
    //     $keptItems = collect();

    //     foreach ($items->groupBy('pickup_guid') as $pickupGuid => $groupedItems) {
    //         $latestHistory = ShipmentStatusHistory::where('order_item_id', $groupedItems->first()->id)
    //             ->orderByDesc('id')
    //             ->first();

    //         $latestCode = $latestHistory?->update_code;
    //         $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

    //         if (!$isTerminal) {
    //             $response = $aramex->cancelPickup(
    //                 $pickupGuid,
    //                 "Pickup supprimé automatiquement - {$userCode} ({$displayName}) supprimé"
    //             );

    //             $hasErrors = $response['HasErrors'] ?? true;
    //             $msg = collect($response['Notifications'] ?? [])->pluck('Message')->implode('; ');
    //             $alreadyCancelled = str_contains(strtolower($msg), 'cannot cancel a cancelled pickup');

    //             if ($hasErrors && !$alreadyCancelled) {
    //                 \Log::warning("UserDeletionService: Aramex cancelPickup failed for pickup {$pickupGuid}", [
    //                     'user_id' => $user->id,
    //                     'message' => $msg,
    //                 ]);
    //             }

    //             $shipmentId = $groupedItems->first()->shipment_id;
    //             $order      = $groupedItems->first()->order;
    //             $vendor     = $groupedItems->first()->vendor;

    //             foreach ($groupedItems as $item) {
    //                 $itemShipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;
    //                 $formattedShipmentId = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1-$2-$3-$4', $itemShipmentId);
    //                 $role = $item->vendor_id === $user->id ? 'Vendeur' : 'Acheteur';

    //                 $item->cancelled_pickup_id   = $item->pickup_id;
    //                 $item->cancelled_pickup_guid = $item->pickup_guid;
    //                 $item->cancelled_shipment_id = $item->shipment_id;
    //                 $item->pickup_cancelled_at   = now();

    //                 $item->pickup_id   = null;
    //                 $item->pickup_guid = null;
    //                 $item->shipment_id = null;
    //                 $item->status      = 'pending';
    //                 $item->info_auto   = "[{$now}] Pickup annulé automatiquement.\nRaison : {$role} « {$displayName} » supprimé.\nID expédition : {$formattedShipmentId}.";
    //                 $item->save();

    //                 if ($item->post) {
    //                     $item->post->statut      = 'vente';
    //                     $item->post->sell_at     = null;
    //                     $item->post->id_user_buy = null;
    //                     $item->post->save();
    //                 }

    //                 $cancelledItems->push($item);
    //             }

    //             if ($order && $vendor) {
    //                 if ($vendor->id !== $user->id) {
    //                     $this->notifySellerPickupCancelled($vendor, $order, $shipmentId, $groupedItems);
    //                 }
    //                 if ($order->buyer && $order->buyer->id !== $user->id) {
    //                     $this->notifyBuyerPickupCancelled($order, $shipmentId, $groupedItems);
    //                 }
    //             }
    //         } else {
    //             foreach ($groupedItems as $item) {
    //                 $shipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;
    //                 $formattedShipmentId = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1-$2-$3-$4', $shipmentId);
    //                 $role = $item->vendor_id === $user->id ? 'Vendeur' : 'Acheteur';

    //                 $item->info_auto = "[{$now}] Ramassage conservé, non annulé.\nRaison : {$role} « {$displayName} » supprimé, expédition déjà en transit (code : {$latestCode}).\nID expédition : {$formattedShipmentId}.";
    //                 $item->save();
    //                 $keptItems->push($item);
    //             }
    //         }

    //         \Log::info("UserDeletionService: processed pickup {$pickupGuid}", [
    //             'user_id'  => $user->id,
    //             'terminal' => $isTerminal,
    //             'items'    => $groupedItems->pluck('id')->all(),
    //         ]);
    //     }

    //     if ($cancelledItems->isNotEmpty()) {
    //         $this->notifyAdminsUserDeletedCancelled($user, $displayName, $cancelledItems);
    //     }

    //     if ($keptItems->isNotEmpty()) {
    //         $this->notifyAdminsUserDeletedKept($user, $displayName, $keptItems);
    //     }
    // }
    public function handlePickupCancellations(User $user): void
{
    try {
        $sellerItems = OrdersItem::where('vendor_id', $user->id)
            ->whereNotNull('pickup_guid')
            ->get();

        $buyerItems = OrdersItem::whereHas('order', fn($q) => $q->where('buyer_id', $user->id))
            ->whereNotNull('pickup_guid')
            ->get();

        $items = $sellerItems->merge($buyerItems)->unique('id')
            ->load('order.buyer', 'vendor', 'post');

        if ($items->isEmpty()) {
            \Log::info('UserDeletionService: no items found, nothing to cancel', ['user_id' => $user->id]);
            return;
        }

        $aramex = app(AramexService::class);
        $now = Carbon::now()->format('Y-m-d H:i');
        $userCode = 'U' . (1000 + $user->id);
        $displayName = $user->username_deleted ?? $user->username;
        $cancelledItems = collect();
        $keptItems = collect();

        foreach ($items->groupBy('pickup_guid') as $pickupGuid => $groupedItems) {
            \Log::info('UserDeletionService: processing pickup group', ['pickup_guid' => $pickupGuid, 'user_id' => $user->id]);

            $latestHistory = ShipmentStatusHistory::where('order_item_id', $groupedItems->first()->id)
                ->orderByDesc('id')
                ->first();

            $latestCode = $latestHistory?->update_code;
            $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

            if (!$isTerminal) {
                $response = $aramex->cancelPickup(
                    $pickupGuid,
                    "Pickup supprimé automatiquement - {$userCode} ({$displayName}) supprimé"
                );

                \Log::info('UserDeletionService: aramex response', ['pickup_guid' => $pickupGuid, 'response' => $response]);

                $hasErrors = $response['HasErrors'] ?? true;
                $msg = collect($response['Notifications'] ?? [])->pluck('Message')->implode('; ');
                $alreadyCancelled = str_contains(strtolower($msg), 'cannot cancel a cancelled pickup');

                if ($hasErrors && !$alreadyCancelled) {
                    \Log::warning("UserDeletionService: Aramex cancelPickup failed for pickup {$pickupGuid}", [
                        'user_id' => $user->id,
                        'message' => $msg,
                    ]);
                }

                $shipmentId = $groupedItems->first()->shipment_id;
                $order      = $groupedItems->first()->order;
                $vendor     = $groupedItems->first()->vendor;

                foreach ($groupedItems as $item) {
                    $itemShipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;
                    $formattedShipmentId = $itemShipmentId
                        ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1-$2-$3-$4', $itemShipmentId)
                        : 'N/A';
                    $role = $item->vendor_id === $user->id ? 'Vendeur' : 'Acheteur';

                    $item->cancelled_pickup_id   = $item->pickup_id;
                    $item->cancelled_pickup_guid = $item->pickup_guid;
                    $item->cancelled_shipment_id = $item->shipment_id;
                    $item->pickup_cancelled_at   = now();

                    $item->pickup_id   = null;
                    $item->pickup_guid = null;
                    $item->shipment_id = null;
                    $item->status      = 'pending';
                    $item->info_auto   = "[{$now}] Pickup annulé automatiquement.\nRaison : {$role} « {$displayName} » supprimé.\nID expédition : {$formattedShipmentId}.";
                    $item->save();

                    \Log::info('UserDeletionService: item updated', ['item_id' => $item->id]);

                    if ($item->post) {
                        $item->post->statut      = 'vente';
                        $item->post->sell_at     = null;
                        $item->post->id_user_buy = null;
                        $item->post->save();
                    }

                    $cancelledItems->push($item);
                }

                if ($order && $vendor) {
                    if ($vendor->id !== $user->id) {
                        \Log::info('UserDeletionService: notifying seller', ['vendor_id' => $vendor->id]);
                        $this->notifySellerPickupCancelled($vendor, $order, $shipmentId, $groupedItems);
                    }
                    if ($order->buyer && $order->buyer->id !== $user->id) {
                        \Log::info('UserDeletionService: notifying buyer', ['buyer_id' => $order->buyer->id]);
                        $this->notifyBuyerPickupCancelled($order, $shipmentId, $groupedItems);
                    }
                }
            } else {
                foreach ($groupedItems as $item) {
                    $shipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;
                    $formattedShipmentId = $shipmentId
                        ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1-$2-$3-$4', $shipmentId)
                        : 'N/A';
                    $role = $item->vendor_id === $user->id ? 'Vendeur' : 'Acheteur';

                    $item->info_auto = "[{$now}] Ramassage conservé, non annulé.\nRaison : {$role} « {$displayName} » supprimé, expédition déjà en transit (code : {$latestCode}).\nID expédition : {$formattedShipmentId}.";
                    $item->save();
                    $keptItems->push($item);
                }
            }
        }

        \Log::info('UserDeletionService: about to notify admins', [
            'cancelled_count' => $cancelledItems->count(),
            'kept_count' => $keptItems->count(),
        ]);

        if ($cancelledItems->isNotEmpty()) {
            $this->notifyAdminsUserDeletedCancelled($user, $displayName, $cancelledItems);
        }

        if ($keptItems->isNotEmpty()) {
            $this->notifyAdminsUserDeletedKept($user, $displayName, $keptItems);
        }

        \Log::info('UserDeletionService: handlePickupCancellations completed successfully', ['user_id' => $user->id]);

    } catch (\Throwable $e) {
        \Log::error('UserDeletionService: handlePickupCancellations CRASHED', [
            'user_id' => $user->id,
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
        ]);
        throw $e; // re-throw so the controller's own catch still returns the 500
    }
}

    private function notifyAdminsUserDeletedCancelled(User $user, string $displayName, $cancelledItems): void
    {
        $userCode = 'U' . (1000 + $user->id);

        $itemsList = $cancelledItems
            ->map(fn($item) => 'CMD-' . ($item->order_id ?? '?') . ' / P' . $item->post_id)
            ->implode(', ');

        $count = $cancelledItems->count();

        $notification = new notifications();
        $notification->titre = $displayName . ' supprimé – ' . $count . ' pickup(s) annulé(s) automatiquement';
        $notification->id_user = $user->id;
        $notification->type = 'user_deleted_pickup_auto_cancelled';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = "L'utilisateur {$userCode} ({$displayName}) a été supprimé. "
            . $count . ' pickup(s) Aramex ont été annulés automatiquement : ' . $itemsList . '.';
        $notification->save();

        event(new AdminEvent($displayName . ' supprimé – pickups annulés automatiquement.'));

        \Log::info("UserDeletionService: admin notified of auto-cancellations", [
            'user_id' => $user->id,
            'items_count' => $count,
        ]);
    }

    private function notifyAdminsUserDeletedKept(User $user, string $displayName, $keptItems): void
    {
        $userCode = 'U' . (1000 + $user->id);

        $itemsList = $keptItems
            ->map(fn($item) => 'CMD-' . ($item->order_id ?? '?') . ' / P' . $item->post_id)
            ->implode(', ');

        $count = $keptItems->count();

        $notification = new notifications();
        $notification->titre = $displayName . ' supprimé – ' . $count . ' ramassage(s) conservé(s), à traiter manuellement';
        $notification->id_user = $user->id;
        $notification->type = 'user_deleted_pickup_kept';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = "L'utilisateur {$userCode} ({$displayName}) a été supprimé, mais "
            . $count . ' ramassage(s) étaient déjà en transit et n\'ont pas pu être annulés : ' . $itemsList . '.';
        $notification->save();

        event(new AdminEvent($displayName . ' supprimé – ramassages conservés à traiter manuellement.'));

        \Log::info("UserDeletionService: admin notified of kept pickups", [
            'user_id' => $user->id,
            'items_count' => $count,
        ]);
    }
}
