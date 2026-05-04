<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSlotNotification extends Model
{
    protected $fillable = [
        'expert_id',
        'booking_slot_id',
        'sent_at',
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
