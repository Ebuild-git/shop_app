<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user, $token;
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->from('no-reply@apa.tn', 'SHOP');
        $this->subject('Confirmation de votre inscription !');
    }

    public function build()
    {
        return $this->view('Mails.verification');
    }
}
