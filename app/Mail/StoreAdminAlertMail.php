<?php

namespace App\Mail;

use App\Models\BulkOrder;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StoreAdminAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ?Order $order = null,
        public ?BulkOrder $bulkOrder = null
    ) {
    }

    public function build(): self
    {
        $subject = $this->bulkOrder
            ? 'New Genchess Bulk Quote Request'
            : 'New Genchess Store Order';

        return $this->subject($subject)->view('emails.store-admin-alert');
    }
}

