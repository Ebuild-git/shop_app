<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class VenteConfirmee extends Mailable
{
    use Queueable, SerializesModels;
    public $seller, $buyerPseudo, $articlesWithGain, $salutation;
    public $pickupContact;
    public $orderId;

    public function __construct($seller, $buyerPseudo, $articlesWithGain, $salutation, $pickupContact, $orderId)
    {
        $this->seller = $seller;
        $this->buyerPseudo = $buyerPseudo;
        $this->articlesWithGain = $articlesWithGain;
        $this->salutation = $salutation;
        $this->pickupContact = $pickupContact;
        $this->orderId = $orderId;
    }


    /**
     * Build the message.
     */
    public function build()
    {
        App::setLocale($this->seller->locale ?? 'fr');
        return $this->from('shopin@fresh-home.store', 'SHOPIN')
                    ->subject("Votre article a été commandé")
                    ->view('Mails.sellerNotification');
    }
}
