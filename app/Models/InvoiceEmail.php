<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceEmail extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'recipient_email', 'email_content','invoice_number','first_name','first_name','last_name','quantity','transaction_amount','additional_note'];


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
