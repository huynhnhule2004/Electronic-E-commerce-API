<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $orderNumber,
        public readonly string $customerName,
        public readonly float $totalAmount
    ) {
    }

    public function build(): self
    {
        return $this->subject('Order Confirmation')
            ->view('emails.order-confirmation');
    }
}
