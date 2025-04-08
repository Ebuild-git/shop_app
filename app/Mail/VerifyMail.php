<?php

namespace App\Mail;

use App\Models\categories;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user, $token,$categorie;
    public function __construct($user, $token)
    {
        //get one random categorie
        $this->categorie = categories::first();
        $this->user = $user;
        $this->token = $token;
        $this->from('do-not-reply@b-and-b.website', 'SHOPIN');
        $this->subject('Confirmation de votre inscription !');

    }

    public function build()
    {
        return $this->view('Mails.verification');
    }
}
