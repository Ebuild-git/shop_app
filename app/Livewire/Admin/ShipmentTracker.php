<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\AramexService;

class ShipmentTracker extends Component
{
    public $shipmentId;   // Shipment ID (to be tracked)
    public $shipmentStatus;  // Shipment Status to display
    public $errorMessage;    // Error message if something goes wrong

    protected $rules = [
        'shipmentId' => 'required|numeric',
    ];

    public function trackShipment()
    {
        $this->validate();
        $aramexService = new AramexService();
        $response = $aramexService->trackShipment($this->shipmentId);

        if ($response && isset($response['status'])) {
            $this->shipmentStatus = $response['status'];
            $this->errorMessage = '';
        } else {
            $this->shipmentStatus = 'Statut non disponible';
            $this->errorMessage = 'Le suivi n\'a pas pu être trouvé. Veuillez vérifier l\'ID.';
        }
    }
    public function render()
    {
        return view('livewire.admin.shipment-tracker');
    }
}
