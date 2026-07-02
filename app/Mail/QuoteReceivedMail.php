<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We've Received Your Quote Request — MapZoon",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quote-received',
            with: [
                'lead' => $this->lead,
                'settings' => SiteSetting::current(),
            ],
        );
    }
}
