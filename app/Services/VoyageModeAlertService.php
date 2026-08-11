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
        $cancelledItems = collect();

        // Group by pickup_guid since cancelling is per-pickup, not per-item
        foreach ($items->groupBy('pickup_guid') as $pickupGuid => $groupedItems) {
            $latestCode = $groupedItems->first()->latestShipmentHistory?->update_code;
            $isTerminal = in_array($latestCode, self::TERMINAL_CODES, true);

            if ($isTerminal) {
                foreach ($groupedItems as $item) {
                    $item->info_auto = "[{$now}] Utilisateur {$user->username} (#{$userCode}) en mode voyage – Ramassage déjà pris en charge par Aramex (statut {$latestCode}) – à traiter manuellement si besoin";
                    $item->save();
                }

                // 🚨 activation after pickup was already completed -> admin must contact Aramex
                $this->notifyAdminActivatedAfterPickup($user, $groupedItems->first(), true);
                continue;
            }

            $comment = "Pickup supprimé automatiquement - {$userCode} ({$user->username}) en mode voyage";

            $response = $aramex->cancelPickup($pickupGuid, $comment);
            $hasErrors = $response['HasErrors'] ?? true;
            $msg = collect($response['Notifications'] ?? [])->pluck('Message')->implode('; ');
            $alreadyCancelled = str_contains(strtolower($msg), 'cannot cancel a cancelled pickup');

            if ($hasErrors && !$alreadyCancelled) {
                foreach ($groupedItems as $item) {
                    $item->info_auto = "[{$now}] Utilisateur {$user->username} (#{$userCode}) en mode voyage – Ramassage déjà en cours – à traiter manuellement";
                    $item->save();
                }

                // activation while pickup is still in progress (not yet completed) -> admin should review
                $this->notifyAdminActivatedAfterPickup($user, $groupedItems->first(), false);
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

                $item->cancelled_pickup_id   = $item->pickup_id;
                $item->cancelled_pickup_guid = $item->pickup_guid;
                $item->cancelled_shipment_id = $item->shipment_id;
                $item->pickup_cancelled_at   = now();

                $item->pickup_id   = null;
                $item->pickup_guid = null;
                $item->shipment_id = null;
                $item->status      = 'pending';
                $item->info_auto   = "[{$now}] Pickup annulé automatiquement.\nRaison : {$role} « {$user->username} » (#{$userCode}) en voyage.\nID expédition : {$formattedShipmentId}.";
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

        if ($cancelledItems->isNotEmpty()) {
            $this->notifyAdminsCancelled($user, $cancelledItems); // FYI, auto-handled
        }
    }

    /**
     * Entry point for when a user turns Travel Mode OFF.
     *
     * Called from:
     *  - Livewire\User\ModeToggle::toggleVoyageMode() (no specific order in context)
     *  - Api\...\ProfileController::update() when voyage_mode flips true -> false
     *    (also no specific order in context there)
     *
     * If you ever have a specific order/item in context, pass it as
     * $orderItem so exactly one notification is sent for that order.
     * Otherwise this notifies once per order that still has an active
     * Aramex pickup tied to the user (mirrors the activation query) —
     * orders with no open pickup are skipped since there's nothing for
     * admin to review there.
     *
     * Every affected item also gets its info_auto stamped, same as on
     * activation, so the deactivation shows up in the order's history.
     */
    public function handleVoyageModeDeactivated(User $user, ?OrdersItem $orderItem = null): void
    {
        $now = Carbon::now()->format('Y-m-d H:i');
        $userCode = 'U' . (1000 + $user->id);
        $infoAuto = "[{$now}] Utilisateur {$user->username} (#{$userCode}) a désactivé le mode voyage.";

        if ($orderItem) {
            $orderItem->info_auto = $infoAuto;
            $orderItem->save();

            $this->notifyAdminDeactivated($user, $orderItem->order, $orderItem->vendor_id);
            return;
        }

        $items = OrdersItem::where(function ($query) use ($user) {
                $query->where('vendor_id', $user->id)
                      ->orWhereHas('order', function ($q) use ($user) {
                          $q->where('buyer_id', $user->id);
                      });
            })
            ->whereNotNull('pickup_guid')
            ->with('order')
            ->get()
            ->groupBy('order_id');

        foreach ($items as $groupedItems) {
            foreach ($groupedItems as $item) {
                $item->info_auto = $infoAuto;
                $item->save();
            }

            $first = $groupedItems->first();
            $this->notifyAdminDeactivated($user, $first->order, $first->vendor_id);
        }

        // Items previously auto-cancelled by voyage mode -> restore the post
        $cancelledItems = OrdersItem::where(function ($query) use ($user) {
                $query->where('vendor_id', $user->id)
                    ->orWhereHas('order', fn($q) => $q->where('buyer_id', $user->id));
            })
            ->whereNotNull('cancelled_pickup_guid')
            ->whereNull('pickup_guid')
            ->with('order', 'post')
            ->get();

        foreach ($cancelledItems as $item) {
            $this->restorePostAfterDeactivation($item);
            $item->info_auto = $infoAuto;
            $item->save();
        }
    }

    private function restorePostAfterDeactivation(OrdersItem $item): void
    {
        if (!$item->post || !$item->cancelled_pickup_guid || $item->pickup_guid) {
            return;
        }

        $order = $item->order;

        if (!$order) {
            return;
        }

        $item->post->statut      = 'vendu';
        $item->post->sell_at     = $order->created_at;
        $item->post->id_user_buy = $order->buyer_id;
        $item->post->save();
    }

    /**
     * 🚨 Admin notification: Travel Mode activated after (or during) a pickup.
     *
     * Pickup Status / Action Required depend on whether the pickup has
     * already completed:
     *  - Pickup Completed  -> "Contact Aramex to hold or reschedule the delivery."
     *  - Anything earlier  -> "Review the order"
     */
    private function notifyAdminActivatedAfterPickup(User $user, OrdersItem $item, bool $isTerminal): void
    {
        $order = $item->order;

        if (!$order) {
            return;
        }

        $roleLabel = $item->vendor_id === $user->id
            ? trans('voyage_mode.admin.activated.seller_label')
            : trans('voyage_mode.admin.activated.buyer_label');

        $pickupStatus = $isTerminal
            ? trans('voyage_mode.admin.activated.pickup_status_completed')
            : trans('voyage_mode.admin.activated.pickup_status_in_progress');

        $actionRequired = $isTerminal
            ? trans('voyage_mode.admin.activated.action_contact_aramex')
            : trans('voyage_mode.admin.activated.action_review_order');

        $title = trans('voyage_mode.admin.activated.title', ['role' => $roleLabel]);
        $eventTime = Carbon::now()->format('d/m/Y – H:i');

        $message = $title . "\n"
            . trans('voyage_mode.admin.activated.order') . ': CMD-' . ($order->id ?? '?') . "\n"
            . $roleLabel . ': ' . $user->username . "\n"
            . trans('voyage_mode.admin.activated.pickup_status') . ': ' . $pickupStatus . "\n"
            . trans('voyage_mode.admin.activated.event_time') . ': ' . $eventTime . "\n"
            . trans('voyage_mode.admin.activated.action_required') . ': ' . $actionRequired;

        $notification = new notifications();
        $notification->titre = $title;
        $notification->id_user = $user->id;
        $notification->type = 'voyage_mode_pending_pickup';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = $message;
        $notification->save();

        event(new AdminEvent($title . ' – CMD-' . ($order->id ?? '?')));

        \Log::info('VoyageModeAlertService: admin notified (activated after pickup)', [
            'user_id'  => $user->id,
            'order_id' => $order->id ?? null,
            'terminal' => $isTerminal,
        ]);
    }

    /**
     * Admin notification: Travel Mode deactivated.
     */
    private function notifyAdminDeactivated(User $user, $order, $vendorId): void
    {
        if (!$order) {
            return;
        }

        $roleLabel = $vendorId === $user->id
            ? trans('voyage_mode.admin.deactivated.seller_label')
            : trans('voyage_mode.admin.deactivated.buyer_label');

        $title = trans('voyage_mode.admin.deactivated.title', [
            'role'     => $roleLabel,
            'username' => $user->username,
        ]);
        $eventTime = Carbon::now()->format('d/m/Y – H:i');

        $message = $title . "\n"
            . trans('voyage_mode.admin.deactivated.order') . ': CMD-' . ($order->id ?? '?') . "\n"
            . trans('voyage_mode.admin.deactivated.status_label') . ': ' . trans('voyage_mode.admin.deactivated.status_value') . "\n"
            . trans('voyage_mode.admin.deactivated.event_time') . ': ' . $eventTime . "\n"
            . trans('voyage_mode.admin.deactivated.action_required') . ': ' . trans('voyage_mode.admin.deactivated.action_review_order');

        $notification = new notifications();
        $notification->titre = $title;
        $notification->id_user = $user->id;
        $notification->type = 'voyage_mode_deactivated';
        $notification->destination = 'admin';
        $notification->url = '/admin/client/' . $user->id . '/view';
        $notification->message = $message;
        $notification->save();

        event(new AdminEvent($title . ' – CMD-' . ($order->id ?? '?')));

        \Log::info('VoyageModeAlertService: admin notified (deactivated)', [
            'user_id'  => $user->id,
            'order_id' => $order->id ?? null,
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
