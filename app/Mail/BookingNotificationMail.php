<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $type)
    {
        $this->booking = $booking;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'created_seeker' => 'Pemesanan Kos Berhasil Dibuat - Mataram Stay',
            'created_owner' => 'Pengajuan Sewa Baru Masuk - Mataram Stay',
            'proof_uploaded' => 'Bukti Pembayaran Baru Diunggah - Mataram Stay',
            'payment_success_seeker' => 'Pembayaran Berhasil & Sewa Aktif - Mataram Stay',
            'payment_success_owner' => 'Pembayaran Sewa Diterima - Mataram Stay',
            'cancelled_seeker' => 'Pemesanan Kos Dibatalkan - Mataram Stay',
            'overbooked_seeker' => 'Pemberitahuan Refund Transaksi (Kamar Penuh) - Mataram Stay',
            'overbooked_admin' => '[PENTING] Transaksi Overbooked Terdeteksi - Mataram Stay',
            default => 'Notifikasi Pemesanan - Mataram Stay',
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
            view: 'emails.booking_notification',
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
