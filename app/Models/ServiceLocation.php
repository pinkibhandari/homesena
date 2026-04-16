<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLocation extends Model
{
    protected $fillable = [
        'address',
        'latitude',
        'longitude',
        'status',
    ];

    // 🔥 Active Scope
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // 🔥 Get Status Text (Accessor)
    public function getStatusTextAttribute()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }
}
