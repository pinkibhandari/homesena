<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'status',
    ];

    /**
     * Accessor: Show time in AM/PM format automatically
     */
    public function getStartTimeFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->start_time)->format('h:i A');
    }
}