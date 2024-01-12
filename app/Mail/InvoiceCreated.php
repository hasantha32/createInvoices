<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $invoiceTitle;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $invoiceTitle)
    {
        $this->customer = $customer;
        $this->invoiceTitle = $invoiceTitle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invoice Created')->view('emails.invoice-created');
    }
}
