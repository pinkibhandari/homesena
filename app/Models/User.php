<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\FixedUserException;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'profile_image',
        'password',
        'phone',
        'otp',
        'role',
        'remember_token',
        'email_verified_at',
        'otp_expires_at',
        'status',
        'profile_completed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


     protected static function boot()
    {
        parent::boot();
        static::updating(function ($user) {
            if ($user->is_fixed) {
                throw new FixedUserException("Fixed user cannot be updated");
            }
        });

        static::deleting(function ($user) {
            if ($user->is_fixed) {
                throw new FixedUserException("Fixed user cannot be deleted");
            }
        });
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_experts', 'expert_id', 'service_id'); // expert_id ==>user's table id for expert
    }

    public function expertSlots()
    {
        return $this->hasMany(BookingSlot::class, 'expert_id');
    }
    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'expert_id');
    }

    public function ratingStat()
    {
        return $this->hasOne(ExpertRatingStat::class, 'expert_id');
    }

    public function expertDetail()
    {
        return $this->hasOne(ExpertDetail::class, 'user_id');
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }
public function onlineLogs()
{
    return $this->hasMany(ExpertOnlineLog::class, 'user_id');
}

    // scope for available experts
    
}
