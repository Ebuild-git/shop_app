<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\AramexService;
use App\Models\Shipment;
use App\Traits\ShipmentStatusTrait;
use Livewire\Attributes\Url;

class Orders extends Component
{
    public $clientInfo = [];

    #[Url(as: 'shipment_id')]
    public $shipmentId = '';

    public $trackingResponse = null;
    public $error = null;

    public function mount()
    {
        $this->clientInfo = [
            'UserName'           => env('ARAMEX_API_USERNAME'),
            'Password'           => env('ARAMEX_API_PASSWORD'),
            'Version'            => env('ARAMEX_API_VERSION'),
            'AccountNumber'      => env('ARAMEX_ACCOUNT_NUMBER'),
            'AccountPin'         => env('ARAMEX_ACCOUNT_PIN'),
            'AccountEntity'      => env('ARAMEX_ACCOUNT_ENTITY'),
            'AccountCountryCode' => env('ARAMEX_ACCOUNT_COUNTRY_CODE'),
            'Source'             => env('ARAMEX_SOURCE'),
        ];

        if (empty($this->shipmentId) && request()->filled('shipment_id')) {
            $this->shipmentId = request()->query('shipment_id');
        }

        if (!empty($this->shipmentId)) {
            $this->trackShipment();
        }
    }

    /**
     * The buyer/seller can land here from a notification for an order that
     * had several vendors (several shipment_id's), or type several IDs by
     * hand. Accept comma / space / newline separated values and normalize
     * them into a clean array Aramex's "Shipments" field can take directly.
     */
    private function parseShipmentIds(): array
    {
        $ids = preg_split('/[\s,]+/', (string) $this->shipmentId, -1, PREG_SPLIT_NO_EMPTY);

        return collect($ids)
            ->map(fn ($id) => trim($id))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function trackShipment()
    {
        $this->reset('trackingResponse', 'error');

        $ids = $this->parseShipmentIds();

        if (empty($ids)) {
            $this->error = "Shipment ID is required.";
            return;
        }

        $payload = [
            'ClientInfo'                => $this->clientInfo,
            'GetLastTrackingUpdateOnly' => false,
            'Shipments'                 => $ids, // now always an array, 1 or many
            'Transaction' => [
                'Reference1' => '',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => '',
            ],
        ];

        try {
            $aramexService = new AramexService();
            $response = $aramexService->trackShipment($payload);

            if (isset($response['TrackingResults'])) {
                foreach ($response['TrackingResults'] as &$tracking) {
                    // Some Aramex responses wrap single-shipment 'Value' as an
                    // object instead of an array — normalize it.
                    if (isset($tracking['Value']) && !array_is_list($tracking['Value'])) {
                        $tracking['Value'] = [$tracking['Value']];
                    }
                    foreach ($tracking['Value'] as &$entry) {
                        $entry['NormalizedStatus'] = ShipmentStatusTrait::getShipmentStatus($entry['UpdateCode']);
                    }
                }
            }
            $this->trackingResponse = $response;
        } catch (\Exception $e) {
            $this->error = "Tracking failed: " . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.user.orders');
    }
}
