<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Email;
use App\Models\Order;
use App\Models\Invoice;
use App\Mail\BookingMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $invoiceId, public int $orderId, public int $userId, public int $emailId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // Find the invoice, order, and user by their IDs
        $invoice = Invoice::findOrFail($this->invoiceId);
        $order = Order::findOrFail($this->orderId);
        $user = User::findOrFail($this->userId);
        $mail = Email::findOrFail($this->emailId);
        // Log::info('Sending email for invoice ID: ' . $user->email);
        // Send the email using the Mailable class
        // Mail::to($user->email)->send(new BookingMail($invoice, $order, $user, $mail));
        Mail::to('agusalfandi8@gmail.com')->send(new BookingMail($invoice, $order, $user, $mail));
    }
}
