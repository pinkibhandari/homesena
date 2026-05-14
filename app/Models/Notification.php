<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceLocation;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'send_type',
        'location_id',
        'user_type',
        'schedule_type',
        'scheduled_at',
        'is_sent',
        'sent_at',
        'status'
    ];

    // Location Relation
    public function location()
    {
        return $this->belongsTo(ServiceLocation::class, 'location_id');
    }
}