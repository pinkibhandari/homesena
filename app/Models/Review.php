<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'booking_slot_id',
        'user_id',
        'expert_id',
        'rating',
        'review',
        'would_recommend'
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function slot()
    {
        return $this->belongsTo(BookingSlot::class, 'booking_slot_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
