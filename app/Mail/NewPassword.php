<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $token,$user;
    public function __construct($token,$user)
    {
        $this->user = $user;
        $this->token=$token;
        $this->from('do-not-reply@b-and-b.website', 'SHOPIN');
        $this->subject('REINITIALISATION DE MOT DE PASSE !');
    }


    public function build()
    {
        return $this->view('Mails.reset-password');
    }
}
