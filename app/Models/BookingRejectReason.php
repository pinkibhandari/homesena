<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRejectReason extends Model
{
    use HasFactory;

    protected $table = 'expert_booking_reject_reasons';

    protected $fillable = [
        'title',
        'status',
    ];
}