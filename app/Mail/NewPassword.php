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
    public $new_password,$user;
    public function __construct($new_password,$user)
    {
        $this->user = $user;
        $this->new_password=$new_password;
        $this->from('no-reply@apa.tn', 'SHOP');
        $this->subject('NOUVEAU MOT DE PASSE !');
    }


    public function build()
    {
        return $this->view('Mails.reset-password');
    }
}
