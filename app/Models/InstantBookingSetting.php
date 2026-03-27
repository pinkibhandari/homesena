<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstantBookingSetting extends Model
{
    protected $fillable = [
        'id',
        'duration_minutes',	
        'price',
        'discount_price',	

    ];
}
