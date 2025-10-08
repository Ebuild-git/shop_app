<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\posts;
use App\Models\Commande;
use App\Services\AramexService;

class UpdateAramexStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-aramex-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update post statuses based on Aramex tracking API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $commandes = Commande::with('post')
            ->whereHas('post', function ($query) {
                $query->where('statut', '!=', 'livré');
            })
            ->whereNotNull('shipment_id')
            ->get();

        if ($commandes->isEmpty()) {
            $this->info('No pending shipments to track.');
            return;
        }

        $aramexService = new AramexService();
        $updatedCount = 0;

        foreach ($commandes as $commande) {
            try {
                $payload = [
                    'ClientInfo' => $aramexService->getClientInfo(),
                    'GetLastTrackingUpdateOnly' => true,
                    'Shipments' => [$commande->shipment_id],
                    'Transaction' => [
                        'Reference1' => 'CMD-' . $commande->id,
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
                    if ($commande->post->statut !== $newStatut) {
                        $commande->post->update(['statut' => $newStatut]);
                        $this->info("Updated Post ID {$commande->post->id} (Shipment: {$commande->shipment_id}) to status: {$newStatut}");
                        $updatedCount++;
                    }
                } else {
                    $this->warn("No tracking data for Shipment ID: {$commande->shipment_id}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to track Shipment ID {$commande->shipment_id}: " . $e->getMessage());
            }
        }

        $this->info("Aramex status update completed. {$updatedCount} posts updated.");
    }
    protected function mapAramexToStatut($updateCode)
    {
        return match ($updateCode) {
            'SH001', 'SH014', 'SH203' => 'préparation',
            'SH012' => 'ramassée',
            'SH003', 'SH004', 'SH073', 'SH252' => 'en cours de livraison',
            'SH005', 'SH006', 'SH007', 'SH154', 'SH234', 'SH496' => 'livré',
            'SH006' => 'en cours de livraison',
            'SH076' => 'en voyage',
            'SH008' => 'retourné',
            'SH033', 'SH043', 'SH294', 'SH480' => 'refusé',
            default  => 'livraison',
        };
    }
}
