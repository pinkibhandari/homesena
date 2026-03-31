<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertDetail extends Model
{
    protected $fillable = [
        'id',	
        'user_id',
     	'registration_code',
        // 'onboarding_agent_code',
        'training_center_id',
        // 'work_schedule',
        // 'is_available',
        'is_online',
        'approval_status',
        'approved_at',
        'approved_by',
        // 'current_latitude' ,
        // 'current_longitude' ,
        // 'last_location_update'         
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingCenter()
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(ExpertEmergencyContact::class, 'expert_detail_id');
    }
}
