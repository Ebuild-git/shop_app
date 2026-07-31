<?php

namespace App\Services\Concerns;

use App\Events\UserEvent;
use App\Mail\PickupCancelled;
use App\Models\notifications;
use App\Models\Order;
use App\Services\FcmService;
use Illuminate\Support\Facades\Mail;

trait NotifiesPickupCancellation
{
    protected function notifySellerPickupCancelled($vendor, Order $order, $shipmentId, $vendorItems)
    {
        $vendorLocale = $vendor->locale ?? config('app.locale');
        app()->setLocale($vendorLocale);

        $notification = new notifications();
        $notification->titre = __('notifications.pickup_cancelled_title');
        $notification->id_user_destination = $vendor->id;
        $notification->type = "pickup_cancelled";
        $notification->url = "/informations?section=commandes";
        $notification->message = __('notifications.pickup_cancelled_message', [
            'shipment_id' => $shipmentId,
            'order_id'    => 'CMD-' . $order->id,
        ]);
        $notification->save();

        event(new UserEvent($vendor->id));

        try {
            Mail::to($vendor->email)->send(
                new PickupCancelled($vendor, $order->id, $shipmentId, $vendorItems, 'seller')
            );
        } catch (\Exception $e) {
            logger("❌ Failed to send pickup-cancelled email to seller: {$vendor->email}. Error: " . $e->getMessage());
        }

        app(FcmService::class)->sendToUser(
            $vendor->id,
            __('notifications.pickup_cancelled_title'),
            strip_tags(__('notifications.pickup_cancelled_message', [
                'shipment_id' => $shipmentId,
                'order_id'    => 'CMD-' . $order->id,
            ])),
            [
                'type'            => 'alerte',
                'notification_id' => $notification->id,
                'destination'     => 'user',
                'action'          => 'pickup_cancelled',
                'order_id'        => $order->id,
                'shipment_id'     => $shipmentId,
            ]
        );

        app()->setLocale(config('app.locale'));
    }

    protected function notifyBuyerPickupCancelled(Order $order, $shipmentId, $items)
    {
        $buyer = $order->buyer;
        $buyerLocale = $buyer->locale ?? config('app.locale');
        app()->setLocale($buyerLocale);

        $notification = new notifications();
        $notification->titre = __('notifications.order_pickup_cancelled_title');
        $notification->id_user_destination = $buyer->id;
        $notification->type = "order_pickup_cancelled";
        $notification->url = "/informations?section=commandes";
        $notification->message = __('notifications.order_pickup_cancelled_message', [
            'order_id'    => 'CMD-' . $order->id,
            'shipment_id' => $shipmentId,
        ]);
        $notification->save();

        event(new UserEvent($buyer->id));

        try {
            Mail::to($buyer->email)->send(
                new PickupCancelled($buyer, $order->id, $shipmentId, $items, 'buyer')
            );
        } catch (\Exception $e) {
            logger("❌ Failed to send pickup-cancelled email to buyer: {$buyer->email}. Error: " . $e->getMessage());
        }

        app(FcmService::class)->sendToUser(
            $buyer->id,
            __('notifications.order_pickup_cancelled_title'),
            strip_tags(__('notifications.order_pickup_cancelled_message', [
                'order_id'    => 'CMD-' . $order->id,
                'shipment_id' => $shipmentId,
            ])),
            [
                'type'            => 'alerte',
                'notification_id' => $notification->id,
                'destination'     => 'user',
                'action'          => 'order_pickup_cancelled',
                'order_id'        => $order->id,
            ]
        );

        app()->setLocale(config('app.locale'));
    }
}
