<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceNotifyRequest extends Model
{
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'address',
        'notify'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
