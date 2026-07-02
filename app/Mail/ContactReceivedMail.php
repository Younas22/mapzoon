<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanks for Contacting MapZoon',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-received',
            with: [
                'lead' => $this->lead,
                'settings' => SiteSetting::current(),
            ],
        );
    }
}
