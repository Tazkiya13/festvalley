<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $invoice, protected $order, protected $user, protected $mail)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@festvalley.com', 'FestValley'),
            subject: 'Booking Package Approval Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.booking',
            with: [
                'invoice' => $this->invoice,
                'order' => $this->order,
                'user' => $this->user->name,
                'status' => $this->invoice->status,
                'customer_name' => $this->user->name ?? '',
                'booking_date' => $this->invoice->availableDate->tanggal ?? '',
                'mail_id' => $this->mail->id,
                'mail' => $this->mail, // Include the full mail object for customer form data
                // 'booking_time' => $this->invoice->availableDate->jam ?? '',
                // 'booking_location' => $this->order->location ?? '',
                // 'booking_link' => url('/booking/'.$this->invoice->id),
                // 'contact_link' => url('/contact'),
            ],

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
