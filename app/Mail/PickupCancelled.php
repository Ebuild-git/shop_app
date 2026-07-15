<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class PickupCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient;
    public $orderId;
    public $shipmentId;
    public $items;
    public $recipientType;

    public function __construct($recipient, $orderId, $shipmentId, $items, $recipientType)
    {
        $this->recipient     = $recipient;
        $this->orderId       = $orderId;
        $this->shipmentId    = $shipmentId;
        $this->items         = $items;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        App::setLocale($this->recipient->locale ?? 'fr');

        $view = $this->recipientType === 'buyer'
            ? 'Mails.pickupCancelledBuyer'
            : 'Mails.pickupCancelledSeller';

        return $this->from('contact@shopin.ma', 'SHOPIN')
                    ->subject(__('email2.pickup_cancelled.subject'))
                    ->view($view);
    }
}
