<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    protected $fillable = [
        	'name',
            'city',
            'address',	
            'status'
         ];
}
