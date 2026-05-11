<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSlotLog extends Model
{
    protected $fillable = [
        'expert_id',
        'booking_slot_id',
        'attempt_count',
        'action',
        'sent_at',
        'reason',
        'attempt_count'
    ];

    public function bookingSlot()
    {
        return $this->belongsTo(BookingSlot::class, 'booking_slot_id');
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
    
}
