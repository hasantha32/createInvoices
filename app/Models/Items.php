<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $fillable = ['item_name', 'quantity', 'item_wise_discount', 'unit_price'];

    public function createInvoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
