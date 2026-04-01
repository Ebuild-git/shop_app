<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendMessage extends Mailable
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
        $this->from('shopin@fresh-home.store', 'SHOPIN');
        $this->subject($data['sujet']);
    }

    public function build()
    {
        return $this->view('Mails.Message');
    }
}
