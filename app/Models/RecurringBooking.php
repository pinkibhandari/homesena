<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringBooking extends Model
{
        protected $fillable = [
	           'booking_id',
        	    'expert_id',
            	'slot_date',
                'slot_time',
                'amount',
                'status'
            ];

 
   public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}
