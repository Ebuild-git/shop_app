<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
        $this->from('shopin@ebuild.website', 'SHOPIN');
        $this->subject('Demande de réinitialisation du mot de passe');
    }

    public function build()
    {
        return $this->view('Mails.send_otp')->with('otp', $this->otp);
    }
}
