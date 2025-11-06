<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AramexService;
use App\Models\Shipment;
use App\Models\{Order, OrdersItem};
use App\Models\regions;

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

    public function deletedOrders(Request $request)
    {
        $query = Order::onlyTrashed()->with(['items.post', 'items.vendor', 'buyer'])->orderBy('deleted_at', 'desc');

        // Filter by region
        if ($request->filled('region_id')) {
            $regionId = $request->region_id;
            $query->where(function ($q) use ($regionId) {
                $q->whereHas('items.vendor', fn($q2) => $q2->where('region', $regionId))
                ->orWhereHas('buyer', fn($q2) => $q2->where('region', $regionId));
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                if (preg_match('/^CMD-(\d+)$/i', $search, $matches)) {
                    $id = $matches[1];
                    $q->where('id', $id);
                } else {
                    $q->whereHas('items.vendor', fn($q2) => $q2->where('username', 'like', "%{$search}%"))
                    ->orWhereHas('buyer', fn($q2) => $q2->where('username', 'like', "%{$search}%"))
                    ->orWhere('shipment_id', 'like', "%{$search}%");
                }
            });
        }

        $orders = $query->paginate(10)->appends($request->all());
        $regions = regions::all();
        return view('Admin.shipement.deleted', compact('orders', 'regions'));
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
        $type = $request->input('type');
        $value = $request->input('value');

        $item = OrdersItem::findOrFail($id);
        if ($type === 'post') {
            $item->post?->update(['statut' => $value]);
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

}
