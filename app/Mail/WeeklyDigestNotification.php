<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WeeklyDigestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $activities,
        public string $officerName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Next Frontiers: Weekly Activity Completion Note — {$this->officerName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.weekly-digest',
        );
    }
}