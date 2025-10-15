<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class BookingRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected $email,
        protected $invoice,
        protected $rejection_reason = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@festvalley.com', 'FestValley'),
            subject: 'Booking Update - ' . $this->invoice->package->package_name,
        );
    }

    public function build()
    {
        return $this->subject('Booking Declined - ' . $this->email->package_name)
                    ->view('mails.booking-rejected')
                    ->with([
                        'customer_name' => $this->email->customer_name,
                        'customer_email' => $this->email->customer_email,
                        'customer_phone' => $this->email->customer_phone,
                        'package_name' => $this->email->package_name,
                        'artist_name' => $this->email->artist_name,
                        'event_type' => $this->email->event_type,
                        'event_date' => $this->email->event_date,
                        'event_location' => $this->email->event_location,
                        'event_description' => $this->email->event_description,
                        'special_requirements' => $this->email->special_requirements,
                        'approval_status' => 'rejected',
                        'rejection_reason' => $this->rejection_reason
                    ]);
    }
}
