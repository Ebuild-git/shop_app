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

    public $seller, $post, $buyerPseudo, $articles_panier, $gain;

    /**
     * Create a new message instance.
     */
    public function __construct($seller, $post, $buyerPseudo, $articles_panier, $gain)
    {
        $this->seller = $seller;
        $this->post = $post;
        $this->buyerPseudo = $buyerPseudo;
        $this->articles_panier = $articles_panier;
        $this->gain = $gain;
        $this->from("do-not-reply@apa.tn", 'SHOPIN');
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
