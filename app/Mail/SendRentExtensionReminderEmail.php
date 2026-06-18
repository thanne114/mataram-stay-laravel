<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendRentExtensionReminderEmail extends Mailable
{
    use SerializesModels;

    public $booking;
    public $renewalBooking;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, Booking $renewalBooking)
    {
        $this->booking = $booking;
        $this->renewalBooking = $renewalBooking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat Perpanjangan Sewa Kos - Mataram Stay',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rent_extension_reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
