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

    public function __construct($seller, $buyerPseudo, $articlesWithGain, $salutation)
    {
        $this->seller = $seller;
        $this->buyerPseudo = $buyerPseudo;
        $this->articlesWithGain = $articlesWithGain;
        $this->salutation = $salutation;
    }


    /**
     * Build the message.
     */
    public function build()
    {
        App::setLocale(session('locale', config('app.locale')));
        return $this->from('no_reply@purah-tunisie.shop', 'SHOPIN')
                    ->subject("Votre article a été commandé")
                    ->view('Mails.sellerNotification');
    }
}
