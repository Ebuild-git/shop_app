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

    public $seller, $post, $buyerPseudo;

    /**
     * Create a new message instance.
     */
    public function __construct($seller, $post, $buyerPseudo)
    {
        $this->seller = $seller;
        $this->post = $post;
        $this->buyerPseudo = $buyerPseudo;
        $this->from("no-reply@apa.tn", 'SHOPIN');
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