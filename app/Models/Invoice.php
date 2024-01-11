<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','invoice_title', 'invoice_number', 'due_date', 'additional_note', 'status'];

    public function items()
    {
        return $this->hasMany(Items::class);
    }


    //customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
