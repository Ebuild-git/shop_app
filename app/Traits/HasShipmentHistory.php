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
            $rawHistory = $historyByPost->get($postId, collect());

            // Most recent shipment_id in the post's history = the current one
            $currentShipmentId = $rawHistory->first()?->shipment_id;

            $currentHistory = $rawHistory
                ->filter(fn($row) => $row->shipment_id === $currentShipmentId)
                ->map($mapRow)
                ->values();

            $cancelledHistory = $rawHistory
                ->filter(fn($row) => $row->shipment_id !== $currentShipmentId)
                ->map($mapRow)
                ->values();

            $cancelledShipmentIds = $cancelledHistory->pluck('shipment_id')->unique()->values()->all();

            $result[$postId] = [
                'current_shipment_id'    => $currentShipmentId,
                'cancelled_shipment_ids' => $cancelledShipmentIds,
                'current_history'        => $currentHistory->all(),
                'cancelled_history'      => $cancelledHistory->all(),
            ];
        }

        return $result;
    }
}
