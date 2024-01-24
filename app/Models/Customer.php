<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'email', 'mobile', 'blacklisted', 'blacklist_reason_add', 'blacklist_reason_remove','status'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
