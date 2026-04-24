<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
     protected $fillable = [
        'invoice_number',
        'type',
        'amount',
        'file_path',
        'issued_at'
    ];

    public function invoiceable()
    {
        return $this->morphTo();
    }
}
