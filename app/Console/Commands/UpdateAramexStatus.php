<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrdersItem;
use App\Services\AramexService;
use App\Models\ShipmentStatusHistory;

class UpdateAramexStatus extends Command
{
    protected $signature = 'app:update-aramex-status';
    protected $description = 'Update order item and post statuses based on Aramex tracking API';

    public function handle()
    {
        $orderItems = OrdersItem::with(['order', 'post'])
            ->whereIn('status', [
                'préparation',
                'ramassée',
                'en cours de livraison',
                'livraison',
            ])
            ->whereNotNull('shipment_id')
            ->get();

        if ($orderItems->isEmpty()) {
            $this->info('No pending shipments to track.');
            return;
        }

        $aramexService = new AramexService();
        $updatedCount = 0;
        $errorCount = 0;

        foreach ($orderItems as $item) {
            try {
                $payload = [
                    'ClientInfo' => $aramexService->getClientInfo(),
                    'GetLastTrackingUpdateOnly' => true,
                    'Shipments' => [$item->shipment_id],
                    'Transaction' => [
                        'Reference1' => 'ORD-' . $item->order_id . '-ITEM-' . $item->id,
                        'Reference2' => '',
                        'Reference3' => '',
                        'Reference4' => '',
                        'Reference5' => ''
                    ]
                ];

                $trackingResponse = $aramexService->trackShipment($payload);
                $latestUpdate = $trackingResponse['TrackingResults'][0]['Value'][0]['UpdateCode'] ?? null;

                if ($latestUpdate) {
                    $newItemStatus  = $this->mapAramexToItemStatus($latestUpdate);   // for orders_items.status
                    $newPostStatut  = $this->mapAramexToPostStatut($latestUpdate);   // for posts.statut
                    $newOrderStatus = $this->mapAramexToOrderStatus($latestUpdate);  // for orders.status

                    if ($item->status !== $newItemStatus) {

                        ShipmentStatusHistory::create([
                            'shipment_id'   => $item->shipment_id,
                            'post_id'       => $item->post?->id,
                            'order_item_id' => $item->id,
                            'old_etat'      => $item->status,
                            'new_etat'      => $newItemStatus,
                        ]);

                        // Update OrdersItem status
                        $item->update(['status' => $newItemStatus]);
                        $this->info("Updated Item ID {$item->id} (Order: {$item->order_id}, Shipment: {$item->shipment_id}) to status: {$newItemStatus}");

                        // Update Post statut
                        if ($item->post) {
                            $item->post->update(['statut' => $newPostStatut]);
                            $this->info("Updated Post ID {$item->post->id} statut to: {$newPostStatut}");
                        }

                        // Update Order status
                        if ($item->order && $item->order->status !== $newOrderStatus) {
                            $item->order->update(['status' => $newOrderStatus]);
                            $this->info("Updated Order ID {$item->order_id} status to: {$newOrderStatus}");
                        }

                        $updatedCount++;

                        // If all items delivered → mark order as livrée
                        $this->updateOrderStatusIfComplete($item->order);
                    }
                } else {
                    $this->warn("No tracking data for Shipment ID: {$item->shipment_id}");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to track Shipment ID {$item->shipment_id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Aramex status update completed. {$updatedCount} items updated, {$errorCount} errors.");
    }

    private function updateOrderStatusIfComplete($order)
    {
        $order->refresh();
        $allDelivered = $order->items()->where('status', '!=', 'livré')->doesntExist();

        if ($allDelivered) {
            $order->update(['status' => 'livrée']);
            $this->info("✅ Order ID {$order->id} marked as fully delivered");
        }
    }

    /**
     * orders_items.status
     * Mirrors post statut values (Aramex pipeline stages)
     */
    protected function mapAramexToItemStatus($updateCode): string
    {
        return match ($updateCode) {
            'SH001', 'SH014', 'SH203'              => 'préparation',
            'SH012'                                 => 'ramassée',
            'SH003', 'SH004', 'SH073', 'SH252'     => 'en cours de livraison',
            'SH005', 'SH006', 'SH007',
            'SH154', 'SH234', 'SH496'              => 'livré',
            'SH008'                                 => 'retourné',
            'SH033', 'SH043', 'SH294', 'SH480'     => 'refusé',
            default                                 => 'livraison',
        };
    }

    /**
     * posts.statut
     * Same mapping as item status (both reflect Aramex pipeline)
     */
    protected function mapAramexToPostStatut($updateCode): string
    {
        return $this->mapAramexToItemStatus($updateCode);
    }

    /**
     * orders.status
     * Maps to the dropdown values: Crée, Expédiée, Livrée, Rétablie, Annulée
     */
    protected function mapAramexToOrderStatus($updateCode): string
    {
        return match ($updateCode) {
            'SH001', 'SH014', 'SH203',
            'SH012',
            'SH003', 'SH004', 'SH073', 'SH252'     => 'expédiée',
            'SH005', 'SH006', 'SH007',
            'SH154', 'SH234', 'SH496'              => 'livrée',
            'SH008', 'SH033', 'SH043',
            'SH294', 'SH480'                        => 'annulée',
            default                                 => 'expédiée',
        };
    }
}
