<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ModerationNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $model;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($model, $type)
    {
        $this->model = $model;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'seeker_verified' => 'Identitas Akun Anda Telah Diverifikasi - Mataram Stay',
            'verification_queue_admin' => '[ANTREAN] Verifikasi Identitas Pengguna Baru - Mataram Stay',
            'property_approved' => 'Properti Kos Anda Telah Disetujui & Terbit - Mataram Stay',
            'property_submitted_admin' => '[ANTREAN] Pengajuan Moderasi Properti Kos Baru - Mataram Stay',
            default => 'Pemberitahuan Moderasi - Mataram Stay',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.moderation_notification',
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
