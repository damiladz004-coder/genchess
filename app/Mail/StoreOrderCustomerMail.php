<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StoreOrderCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $mailType = 'order_confirmation'
    ) {
    }

    public function build(): self
    {
        $subject = match ($this->mailType) {
            'payment_confirmation' => 'Genchess Store Payment Confirmed',
            'shipping_update' => 'Genchess Store Shipping Update',
            default => 'Genchess Store Order Confirmation',
        };

        return $this->subject($subject)->view('emails.store-order-customer');
    }
}

