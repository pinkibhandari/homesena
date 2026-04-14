<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertSOS extends Model
{
    protected $table = 'expert_sos';
    protected $fillable = [
        'expert_id',
        'booking_slot_id',
        'latitude',
        'longitude',
        'message',
        'status',
        'resolved_at'
    ];

     public function expert()
        {
            return $this->belongsTo(User::class, 'expert_id');
        }

    public function bookingSlot()
        {
            return $this->belongsTo(BookingSlot::class);
        }
}
