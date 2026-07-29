<?php

namespace App\Traits;

use App\Models\OrdersItem;
use App\Models\ShipmentStatusHistory;
use Illuminate\Support\Collection;

trait HasShipmentHistory
{
    /**
     * Build the current/cancelled shipment history split for a batch of post ids.
     * Returns an associative array keyed by post_id:
     * [
     *   $postId => [
     *      'current_shipment_id'    => string|null,
     *      'cancelled_shipment_ids' => array,
     *      'current_history'        => array,
     *      'cancelled_history'      => array,
     *   ],
     *   ...
     * ]
     */
    protected function getShipmentHistoriesForPosts(array $postIds): array
    {
        $postIds = array_values(array_unique(array_filter($postIds)));

        if (empty($postIds)) {
            return [];
        }

        // Group order items by post_id to know each post's active vs cancelled shipment ids
        $itemsByPost = OrdersItem::whereIn('post_id', $postIds)
            ->get()
            ->groupBy('post_id');

        // Fetch all relevant history rows in one query, grouped by post_id
        $historyByPost = ShipmentStatusHistory::whereIn('post_id', $postIds)
            ->orderByDesc('update_datetime')
            ->get()
            ->groupBy('post_id');

        $mapRow = function ($row) {
            return [
                'id'                 => $row->id,
                'shipment_id'        => $row->shipment_id,
                'order_item_id'      => $row->order_item_id,
                'old_etat'           => $row->old_etat,
                'new_etat'           => $row->new_etat,
                'update_code'        => $row->update_code,
                'update_description' => $row->update_description,
                'update_location'    => $row->update_location,
                'update_datetime'    => optional($row->update_datetime)->format('Y-m-d H:i'),
                'created_at'         => optional($row->created_at)->format('Y-m-d H:i'),
            ];
        };

        $result = [];

        foreach ($postIds as $postId) {
            $items = $itemsByPost->get($postId, collect());

            $currentShipmentIds   = $items->pluck('shipment_id')->filter()->unique()->values();
            $cancelledShipmentIds = $items->pluck('cancelled_shipment_id')->filter()->unique()->values();

            $rawHistory = $historyByPost->get($postId, collect());

            $currentHistory = $rawHistory
                ->filter(fn($row) => $currentShipmentIds->contains($row->shipment_id))
                ->map($mapRow)
                ->values();

            $cancelledHistory = $rawHistory
                ->filter(fn($row) => $cancelledShipmentIds->contains($row->shipment_id))
                ->map($mapRow)
                ->values();

            $result[$postId] = [
                'current_shipment_id'    => $currentShipmentIds->first(),
                'cancelled_shipment_ids' => $cancelledShipmentIds->values()->all(),
                'current_history'        => $currentHistory->all(),
                'cancelled_history'      => $cancelledHistory->all(),
            ];
        }

        return $result;
    }
}
