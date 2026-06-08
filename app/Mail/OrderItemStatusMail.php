<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderItemStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $buyer;
    public $order;
    public $post;
    public $postImage;
    public $type;
    public $subject;
    public $bodyMessage;

    public function __construct($buyer, $order, $post, $postImage, $type, $subject, $bodyMessage)
    {
        $this->buyer       = $buyer;
        $this->order       = $order;
        $this->post        = $post;
        $this->postImage   = $postImage;
        $this->type        = $type;
        $this->subject     = $subject;
        $this->bodyMessage = $bodyMessage;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('Mails.order_item_status');
    }
}
