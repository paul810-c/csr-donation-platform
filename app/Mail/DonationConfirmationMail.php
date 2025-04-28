<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\CampaignDonation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class DonationConfirmationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly CampaignDonation $donation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for your donation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation_confirmation',
            with: [
                'donation' => $this->donation,
            ],
        );
    }

    /**
     * @return array<\Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
