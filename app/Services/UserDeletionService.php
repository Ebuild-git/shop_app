<?php

namespace App\Services;

use App\Models\OrdersItem;
use App\Models\ShipmentStatusHistory;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserDeletionService
{
    /**
     * The update_codes indicating the shipment has already been picked up /
     * is in a terminal transit state – pickup should NOT be cancelled in these cases.
     */
    private const TERMINAL_CODES = ['SH012', 'SH314', 'SH308', 'SH312'];

    /**
     * Cancel any active Aramex pickups for order items linked to a deleted user
     * and write an audit note in `info_auto`.
     *
     * @param User   $user The user being deleted
     * @param string $role Either 'vendeur' or 'acheteur' – used in the audit note
     */
    public function handlePickupCancellations(User $user, string $role): void
    {
        // ── 1. Collect active items (not soft-deleted, have a pickup_guid) ──────
        //    As a seller
        $sellerItems = OrdersItem::where('vendor_id', $user->id)
            ->whereNotNull('pickup_guid')
            ->get();

        //    As a buyer (via the linked order)
        $buyerItems = OrdersItem::whereHas('order', fn($q) => $q->where('buyer_id', $user->id))
            ->whereNotNull('pickup_guid')
            ->get();

        // Merge and deduplicate by primary key
        $items = $sellerItems->merge($buyerItems)->unique('id');

        if ($items->isEmpty()) {
            return;
        }

        $aramex = app(AramexService::class);
        $now = Carbon::now()->format('Y-m-d H:i');

        foreach ($items as $item) {
            $shipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;

            // ── 2. Determine whether the shipment is already at a terminal state ──
            $latestHistory = ShipmentStatusHistory::where('order_item_id', $item->id)
                ->orderByDesc('id')
                ->first();

            $latestCode = $latestHistory?->update_code;

            $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

            if (!$isTerminal) {
                // ── 3a. Shipment NOT yet terminal → cancel pickup via Aramex API ───
                try {
                    $aramex->cancelPickup(
                        $item->pickup_guid,
                        "Compte utilisateur supprimé – {$role}"
                    );
                } catch (\Throwable $e) {
                    \Log::warning("UserDeletionService: Aramex cancelPickup failed for item #{$item->id}", [
                        'pickup_guid' => $item->pickup_guid,
                        'error' => $e->getMessage(),
                    ]);
                    // We still proceed to clear local state and log info_auto
                }

                // Snapshot the pickup trail before clearing
                $item->cancelled_pickup_id = $item->pickup_id;
                $item->cancelled_pickup_guid = $item->pickup_guid;
                $item->cancelled_shipment_id = $item->shipment_id;
                $item->pickup_cancelled_at = now();

                // Clear active pickup data
                $item->pickup_id = null;
                $item->pickup_guid = null;
                $item->shipment_id = null;
                $item->status = 'pending';

                $item->info_auto = "[{$now}] {$role} supprimé – Expédition {$shipmentId} – Pickup annulé automatiquement";
            } else {
                // ── 3b. Shipment IS terminal → keep pickup, just log the note ────
                $item->info_auto = "[{$now}] {$role} supprimé – Expédition {$shipmentId} – Ramassage conservé (code: {$latestCode})";
            }

            $item->save();

            \Log::info("UserDeletionService: processed item #{$item->id}", [
                'user_id' => $user->id,
                'role' => $role,
                'terminal' => $isTerminal,
                'shipment' => $shipmentId,
                'info_auto' => $item->info_auto,
            ]);
        }
    }
}
