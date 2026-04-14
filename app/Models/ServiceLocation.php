<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLocation extends Model
{
    protected $fillable = [
        'address',
        'latitude',
        'longitude',
        'status'
        
    ];
}
