<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'status'
    ];

}
