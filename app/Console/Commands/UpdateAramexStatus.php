<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrdersItem;
use App\Services\AramexService;

class UpdateAramexStatus extends Command
{
    protected $signature = 'app:update-aramex-status';
    protected $description = 'Update order item statuses based on Aramex tracking API';

    public function handle()
    {
        // Get all order items with pending shipments (not yet delivered)
        $orderItems = OrdersItem::with(['order', 'post'])
            ->where('status', '!=', 'livré')
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
                    $newStatut = $this->mapAramexToStatut($latestUpdate);

                    if ($item->status !== $newStatut) {
                        $item->update(['status' => $newStatut]);

                        $this->info("Updated Item ID {$item->id} (Order: {$item->order_id}, Shipment: {$item->shipment_id}) to status: {$newStatut}");
                        $updatedCount++;

                        // Update order status if all items are delivered
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
        // Reload order items to check if all are delivered
        $order->refresh();
        $allDelivered = $order->items()->where('status', '!=', 'livré')->doesntExist();

        if ($allDelivered) {
            $order->update(['status' => 'livré']);
            $this->info("✅ Order ID {$order->id} marked as fully delivered");
        }
    }

    protected function mapAramexToStatut($updateCode)
    {
        return match ($updateCode) {
            'SH001', 'SH014', 'SH203' => 'préparation',
            'SH012' => 'ramassée',
            'SH003', 'SH004', 'SH073', 'SH252' => 'en cours de livraison',
            'SH005', 'SH006', 'SH007', 'SH154', 'SH234', 'SH496' => 'livré',
            'SH076' => 'en voyage',
            'SH008' => 'retourné',
            'SH033', 'SH043', 'SH294', 'SH480' => 'refusé',
            default => 'livraison',
        };
    }
}
