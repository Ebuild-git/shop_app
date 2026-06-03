<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AramexService;
use App\Models\Shipment;
use App\Models\{Order, OrdersItem};
use App\Models\regions;
use Illuminate\Support\Facades\App;


class OrdersController extends Controller
{
    public function myOrders(){
        return view('User.orders');
    }
    public function destroy(Order $order)
    {
        $order->delete();
        $order->update(['status' => 'supprimée']);
        return response()->json(['success' => true]);
    }

    // public function deletedOrders(Request $request)
    // {
    //     $query = Order::onlyTrashed()->with(['items.post', 'items.vendor', 'buyer'])->orderBy('deleted_at', 'desc');

    //     // Filter by region
    //     if ($request->filled('region_id')) {
    //         $regionId = $request->region_id;
    //         $query->where(function ($q) use ($regionId) {
    //             $q->whereHas('items.vendor', fn($q2) => $q2->where('region', $regionId))
    //             ->orWhereHas('buyer', fn($q2) => $q2->where('region', $regionId));
    //         });
    //     }

    //     // Filter by date
    //     if ($request->filled('date')) {
    //         $query->whereDate('created_at', $request->date);
    //     }

    //     // Filter by search
    //     if ($request->filled('search')) {
    //         $search = $request->search;

    //         $query->where(function($q) use ($search) {
    //             if (preg_match('/^CMD-(\d+)$/i', $search, $matches)) {
    //                 $id = $matches[1];
    //                 $q->where('id', $id);
    //             } else {
    //                 $q->whereHas('items.vendor', fn($q2) => $q2->where('username', 'like', "%{$search}%"))
    //                 ->orWhereHas('buyer', fn($q2) => $q2->where('username', 'like', "%{$search}%"))
    //                 ->orWhere('shipment_id', 'like', "%{$search}%");
    //             }
    //         });
    //     }

    //     $orders = $query->paginate(10)->appends($request->all());
    //     $regions = regions::all();
    //     return view('Admin.shipement.deleted', compact('orders', 'regions'));
    // }
    public function deletedOrders(Request $request)
    {
        $query = OrdersItem::onlyTrashed()
            ->with(['post', 'vendor', 'order.buyer'])
            ->orderBy('deleted_at', 'desc');

        // Filter by region
        if ($request->filled('region_id')) {
            $regionId = $request->region_id;
            $query->where(function ($q) use ($regionId) {
                $q->whereHas('vendor', fn($q2) => $q2->where('region', $regionId))
                ->orWhereHas('order.buyer', fn($q2) => $q2->where('region', $regionId));
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('deleted_at', $request->date);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                if (preg_match('/^CMD-(\d+)$/i', $search, $matches)) {
                    $q->where('order_id', $matches[1]);
                } else {
                    $q->whereHas('vendor', fn($q2) => $q2->where('username', 'like', "%{$search}%"))
                    ->orWhereHas('order.buyer', fn($q2) => $q2->where('username', 'like', "%{$search}%"))
                    ->orWhere('shipment_id', 'like', "%{$search}%");
                }
            });
        }

        $items = $query->paginate(10)->appends($request->all());
        $regions = regions::all();

        return view('Admin.shipement.deleted', compact('items', 'regions'));
    }

    public function restore($orderId)
    {
        $order = Order::onlyTrashed()->findOrFail($orderId);
        $order->restore();
        $order->update(['status' => 'Rétablie']);
        return response()->json(['success' => true]);
    }
    public function forceDelete($orderId)
    {
        $order = Order::onlyTrashed()->findOrFail($orderId);
        $order->items()->forceDelete();
        $order->forceDelete();
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        $type  = $request->input('type');
        $value = $request->input('value');

        $item = OrdersItem::findOrFail($id);

        if ($type === 'post') {
            $post = $item->post;
            if ($post) {
                $post->update(['statut' => $value]);

                if ($value === 'livré' && $post->id_user_buy) {
                    $buyer = $post->acheteur;
                    if ($buyer) {
                        $buyerLocale = $buyer->locale ?? config('app.locale');
                        App::setLocale($buyerLocale);

                        $notification = new \App\Models\notifications();
                        $notification->titre               = __('rate_seller_notification_title');
                        $notification->id_user_destination = $post->id_user_buy;
                        $notification->type                = "alerte";
                        $notification->url                 = "/post/" . $post->id;
                        $notification->id_post             = $post->id;
                        $notification->destination         = "user";
                        $notification->message = __('rate_seller_notification_message', [
                            'url'      => route('details_post2', [
                                'id' => $post->id,
                                'titre' => $post->titre,
                            ]),
                            'title'    => $post->titre,
                            'user_url' => url('user/' . $post->id_user),
                            'shopiner' => $post->user_info?->username ?? '',
                        ]);

                        $notification->save();

                        App::setLocale(config('app.locale'));

                        event(new \App\Events\UserEvent($post->id_user_buy));

                        $fcmService = app(\App\Services\FcmService::class);
                        $sent = $fcmService->sendToUser(
                            $post->id_user_buy,
                            __('rate_seller_fcm_title'),
                            __('rate_seller_fcm_body', ['title' => $post->titre]),
                            [
                                'type'            => 'alerte',
                                'notification_id' => $notification->id,
                                'destination'     => 'user',
                                'action'          => 'rate_seller',
                                'post_id'         => $post->id,
                            ]
                        );

                        if ($sent) {
                            \Log::info("FCM notification sent successfully", [
                                'user_id'         => $post->id_user_buy,
                                'notification_id' => $notification->id,
                                'type'            => 'rate_seller'
                            ]);
                        } else {
                            \Log::warning("FCM notification failed to send", [
                                'user_id'         => $post->id_user_buy,
                                'notification_id' => $notification->id,
                                'reason'          => 'User has no FCM token or token invalid'
                            ]);
                        }
                    }
                }
            }
        } elseif ($type === 'order') {
            $order = Order::findOrFail($item->order->id);
            $order->update(['status' => $value]);
        }

        return response()->json(['success' => true]);
    }


    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string',
        ]);
        $order = Order::findOrFail($id);
        $order->note = $request->note;
        $order->save();

        return response()->json(['success' => true]);
    }

    // public function destroyItem(OrdersItem $item)
    // {
    //     $item->delete();
    //     $item->update(['status' => 'supprimée']);
    //     return response()->json(['success' => true]);
    // }

    // public function restoreItem($itemId)
    // {
    //     $item = OrdersItem::onlyTrashed()->findOrFail($itemId);
    //     $item->restore();
    //     return response()->json(['success' => true]);
    // }

    public function destroyItem(OrdersItem $item)
    {
        $post  = $item->post;
        $order = $item->order;

        $item->delete();
        $item->update(['status' => 'supprimée']);

        if ($post) {
            $saleWasInProgress = in_array($post->statut, [
                'préparation', 'en cours de livraison', 'ramassée', 'livraison', 'livré', 'retourné', 'vendu'
            ]);

            if ($saleWasInProgress) {
                $post->statut      = 'vente';
                $post->sell_at     = null;
                $post->id_user_buy = null;
                $post->save();
            }
        }

        // Notify buyer
        if ($order && $order->buyer) {
            $buyer = $order->buyer;

            event(new \App\Events\UserEvent($buyer->id));

            $buyerLocale = $buyer->locale ?? config('app.locale');
            App::setLocale($buyerLocale);

            $notification = new \App\Models\notifications();
            $notification->titre               = __('order_item_cancelled_title');
            $notification->id_user_destination = $buyer->id;
            $notification->type                = 'alerte';
            $notification->url                 = '/informations?section=commandes';
            $notification->message             = __('order_item_cancelled_message', [
                'shipment_id' => 'CMD-' . $order->id,
                'post_title'  => $post?->titre ?? '',
            ]);
            $notification->save();

            App::setLocale(config('app.locale'));

            $fcmService = app(\App\Services\FcmService::class);
            $sent = $fcmService->sendToUser(
                $buyer->id,
                __('order_item_cancelled_title'),
                __('order_item_cancelled_message', [
                    'shipment_id' => 'CMD-' . $order->id,
                    'post_title'  => $post?->titre ?? '',
                ]),
                [
                    'type'            => 'alerte',
                    'notification_id' => $notification->id,
                    'destination'     => 'user',
                    'action'          => 'order_item_cancelled',
                    'order_id'        => $order->id,
                ]
            );

            if ($sent) {
                \Log::info("FCM notification sent successfully", [
                    'user_id'         => $buyer->id,
                    'notification_id' => $notification->id,
                    'type'            => 'order_item_cancelled',
                ]);
            } else {
                \Log::warning("FCM notification failed to send", [
                    'user_id'         => $buyer->id,
                    'notification_id' => $notification->id,
                    'reason'          => 'User has no FCM token or token invalid',
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function restoreItem($itemId)
    {
        $item  = OrdersItem::onlyTrashed()->findOrFail($itemId);
        $item->restore();

        $post  = $item->post;
        $order = $item->order;

        if ($post && $post->statut === 'vente' && is_null($post->sell_at)) {
            $post->statut      = 'préparation';
            $post->sell_at     = $item->created_at;
            $post->id_user_buy = $order->buyer_id ?? null;
            $post->save();
        }

        // Notify buyer
        if ($order && $order->buyer) {
            $buyer = $order->buyer;

            event(new \App\Events\UserEvent($buyer->id));

            $buyerLocale = $buyer->locale ?? config('app.locale');
            App::setLocale($buyerLocale);

            $notification = new \App\Models\notifications();
            $notification->titre               = __('order_item_restored_title');
            $notification->id_user_destination = $buyer->id;
            $notification->type                = 'alerte';
            $notification->url                 = '/informations?section=commandes';
            $notification->message             = __('order_item_restored_message', [
                'shipment_id' => 'CMD-' . $order->id,
                'post_title'  => $post?->titre ?? '',
            ]);
            $notification->save();

            App::setLocale(config('app.locale'));

            $fcmService = app(\App\Services\FcmService::class);
            $sent = $fcmService->sendToUser(
                $buyer->id,
                __('order_item_restored_title'),
                __('order_item_restored_message', [
                    'shipment_id' => 'CMD-' . $order->id,
                    'post_title'  => $post?->titre ?? '',
                ]),
                [
                    'type'            => 'alerte',
                    'notification_id' => $notification->id,
                    'destination'     => 'user',
                    'action'          => 'order_item_restored',
                    'order_id'        => $order->id,
                ]
            );

            if ($sent) {
                \Log::info("FCM notification sent successfully", [
                    'user_id'         => $buyer->id,
                    'notification_id' => $notification->id,
                    'type'            => 'order_item_restored',
                ]);
            } else {
                \Log::warning("FCM notification failed to send", [
                    'user_id'         => $buyer->id,
                    'notification_id' => $notification->id,
                    'reason'          => 'User has no FCM token or token invalid',
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function forceDeleteItem($itemId)
    {
        $item = OrdersItem::onlyTrashed()->findOrFail($itemId);
        $item->forceDelete();
        return response()->json(['success' => true]);
    }

}
