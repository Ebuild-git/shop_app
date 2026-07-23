<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ShipmentConfirmee extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient;
    public $orderId;
    public $shipmentId;
    public $items;
    public $recipientType; // 'buyer' or 'seller'
    public $labelUrl;

    public function __construct($recipient, $orderId, $shipmentId, $items, $recipientType)
    {
        $this->recipient     = $recipient;
        $this->orderId       = $orderId;
        $this->shipmentId    = $shipmentId;
        $this->items         = $items;
        $this->recipientType = $recipientType;
        $this->labelUrl      = route('aramex.label.download', ['shipmentId' => $shipmentId]);
    }

    public function build()
    {
        App::setLocale($this->recipient->locale ?? 'fr');

        $view = $this->recipientType === 'buyer'
            ? 'Mails.shipmentBuyer'
            : 'Mails.shipmentSeller';

        return $this->from('contact@shopin.ma', 'SHOPIN')
                    ->subject(__('email2.shipment.subject'))
                    ->view($view)
                    ->with([
                        'labelUrl' => $this->labelUrl,
                    ]);
    }
}
