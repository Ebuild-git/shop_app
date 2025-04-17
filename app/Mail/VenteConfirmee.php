<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VenteConfirmee extends Mailable
{
    use Queueable, SerializesModels;

    public $seller, $buyerPseudo, $articlesWithGain;

    /**
     * Create a new message instance.
     */
    public function __construct($seller, $buyerPseudo, $articlesWithGain)
    {
        $this->seller = $seller;
        $this->buyerPseudo = $buyerPseudo;
        $this->articlesWithGain = $articlesWithGain;
        $this->from('no_reply@purah-tunisie.shop', 'SHOPIN');
        $this->subject("Votre article a été commandé");
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('Mails.sellerNotification');
    }
}
