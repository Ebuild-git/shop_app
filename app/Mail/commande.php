<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class commande extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user,$articles_panier, $totalShippingFees;
    public function __construct($user,$articles_panier, $totalShippingFees)
    {
        $this->user = $user;
        $this->articles_panier = $articles_panier;
        $this->totalShippingFees = $totalShippingFees;
        $this->from("no-reply@apa.tn", 'SHOPIN');
        $this->subject("Confirmation de votre commande");
    }

    public function build()
    {
        return $this->view('Mails.commande')
        ->with([
            'totalShippingFees' => $this->totalShippingFees,
        ]);
    }
}
