<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HomePromotion extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'promotion_datetime',
        'status'
    ];
}