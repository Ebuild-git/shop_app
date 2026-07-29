<?php

namespace App\Services;

use App\Events\AdminEvent;
use App\Models\notifications;
use App\Models\OrdersItem;
use App\Models\ShipmentStatusHistory;
use App\Models\User;
use Illuminate\Support\Carbon;

class VoyageModeAlertService
{
    /**
     * update_codes indicating the shipment is already picked up / in a terminal
     * transit state — these don't need an admin heads-up since Aramex already
     * has physical custody of the parcel.
     */
    private const TERMINAL_CODES = ['SH012', 'SH314', 'SH308', 'SH312'];

    /**
     * When a seller activates voyage mode, flag any of their active pickups
     * that Aramex hasn't picked up yet, so admin can follow up manually.
     *
     * @param User $user The user activating voyage mode
     */
    public function handleVoyageModeActivated(User $user): void
    {
        $items = OrdersItem::where('vendor_id', $user->id)
            ->whereNotNull('pickup_guid')
            ->get();

        if ($items->isEmpty()) {
            return;
        }

        $now = Carbon::now()->format('Y-m-d H:i');
        $pendingItems = collect();

        foreach ($items as $item) {
            $shipmentId = $item->shipment_id ?? $item->cancelled_shipment_id;

            $latestHistory = ShipmentStatusHistory::where('order_item_id', $item->id)
                ->orderByDesc('id')
                ->first();

            $latestCode = $latestHistory?->update_code;
            $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

            if ($isTerminal) {
                // Already picked up by Aramex — nothing for admin to act on.
                continue;
            }

            $item->info_auto = "[{$now}] Vendeur en mode voyage – Expédition {$shipmentId} – Pickup en attente, à surveiller";
            $item->save();

            $pendingItems->push($item);

            \Log::info("VoyageModeAlertService: flagged item #{$item->id} for admin review", [
                'user_id'  => $user->id,
                'shipment' => $shipmentId,
                'terminal' => $isTerminal,
            ]);
        }

        if ($pendingItems->isEmpty()) {
            return;
        }

        $this->notifyAdmins($user, $pendingItems);
    }

    /**
     * Create an admin-facing notification + broadcast event listing the affected order items.
     */
    private function notifyAdmins(User $user, $pendingItems): void
    {
        $itemsList = $pendingItems
            ->map(fn($item) => 'CMD-' . ($item->order_id ?? '?') . ' / Article #' . $item->id)
            ->implode(', ');

        $count = $pendingItems->count();

        $notification = new notifications();
        $notification->titre = $user->username . ' a activé le mode voyage avec ' . $count . ' ramassage(s) en attente';
        $notification->id_user = $user->id;
        $notification->type = 'voyage_mode_pending_pickup';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = 'L\'utilisateur ' . $user->username . ' (ID ' . $user->id . ') a activé le mode voyage '
            . 'alors que ' . $count . ' article(s) attendent toujours un ramassage Aramex : ' . $itemsList . '.';
        $notification->save();

        event(new AdminEvent($user->username . ' a activé le mode voyage avec des ramassages en attente.'));

        \Log::info("VoyageModeAlertService: admin notified", [
            'user_id' => $user->id,
            'items_count' => $count,
        ]);
    }
}
