<?php

namespace App\Mail;

use App\Models\NewsletterSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;

    public function __construct(NewsletterSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->subject(__('Welcome to Shopin Newsletter'))
            ->view('emails.newsletter-welcome')
            ->with([
                'unsubscribeUrl' => route('newsletter.unsubscribe', $this->subscription->unsubscribe_token)
            ]);
    }
}