<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferEarnSetting extends Model
{
    use HasFactory;

    protected $table = 'refer_earn_settings';

    protected $fillable = [
        'referral_amount',
        'signup_bonus',
    ];
}