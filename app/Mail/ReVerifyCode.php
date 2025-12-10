<?php

namespace App\Mail;

use App\Models\categories;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReVerifyCode extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->from('shopin@ebuild.website', 'SHOPIN');
        $this->subject('Vérification code');
    }

    public function build()
    {
        return $this->view('Mails.re-verification_code');
    }
}

