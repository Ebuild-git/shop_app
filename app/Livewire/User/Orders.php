<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\AramexService;
use App\Models\Shipment;
class Orders extends Component
{
    public $clientInfo = [];
    public $shipmentId = '';
    public $trackingResponse = null;
    public $error = null;

    public function mount(){
        $this->clientInfo = [
        'UserName' => env('ARAMEX_API_USERNAME'),
        'Password' => env('ARAMEX_API_PASSWORD'),
        'Version' => env('ARAMEX_API_VERSION'),
        'AccountNumber' => env('ARAMEX_ACCOUNT_NUMBER'),
        'AccountPin' => env('ARAMEX_ACCOUNT_PIN'),
        'AccountEntity' => env('ARAMEX_ACCOUNT_ENTITY'),
        'AccountCountryCode' => env('ARAMEX_ACCOUNT_COUNTRY_CODE'),
        'Source' => env('ARAMEX_SOURCE'),
        ];

    }
    public function trackShipment()
    {
        $this->reset('trackingResponse', 'error');

        if (empty($this->shipmentId)) {
            $this->error = "Shipment ID is required.";
            return;
        }

        // Build payload
        $payload = [
            'ClientInfo' => $this->clientInfo,
            'GetLastTrackingUpdateOnly' => false,
            'Shipments' => [$this->shipmentId],
            'Transaction' => [
                'Reference1' => '',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => ''
            ]
        ];

        try {
            $aramexService = new AramexService();
            $response = $aramexService->trackShipment($payload);
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
