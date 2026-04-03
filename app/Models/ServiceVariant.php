<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceVariant extends Model
{
    protected $fillable = [
        'service_id',
        'duration_minutes',
        'price',
        'discount_price',  
        // 'tax_percentage',
        'is_active',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

}