<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'base_price',
        'tax_percentage',
        'new_flag',
        'is_active'
    ];

    // Define relationships and other model methods as needed   
    public function experts()
    {
        return $this->belongsToMany(User::class, 'service_experts', 'service_id', 'expert_id');
    }
    public function variants()
    {
        return $this->hasMany(ServiceVariant::class);
    }
  // active variant only
    public function activeVariants()
    {
        return $this->hasMany(ServiceVariant::class)->where('is_active',1); 
    }
}
