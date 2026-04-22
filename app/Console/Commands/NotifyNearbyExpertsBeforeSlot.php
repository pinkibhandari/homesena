<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\BookingSlot;
use App\Models\User;
use App\Services\FirebaseService;

class NotifyNearbyExpertsBeforeSlot extends Command
{
    protected $signature = 'app:notify-nearby-experts-before-slot';
    protected $description = 'Notify nearby experts 30 minutes before slot start';
    public function handle()
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addMinutes(30);

        $slots = BookingSlot::with('booking')
            ->whereBetween('start_time', [
                $targetTime->copy()->startOfMinute(),
                $targetTime->copy()->endOfMinute()
            ])
            ->where('status', 'confirmed')   
            ->whereNull('expert_id')       
            ->where('notified', 0)         
            ->get();

        foreach ($slots as $slot) {

            if (!$slot->booking) 
                continue;

            $lat = $slot->booking->latitude;
            $lng = $slot->booking->longitude;

            // 🔥 Find nearby experts
            $experts = User::selectRaw("
                id, device_token,
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->having("distance", "<=", 5)
            ->where('is_online', 1)
            ->whereNotNull('device_token')
            ->orderBy("distance")
            ->limit(20)
            ->get();

            foreach ($experts as $expert) {

                FirebaseService::sendNotification(
                    $expert->device_token,
                    'New Booking Available',
                    'A confirmed booking near you starts in 30 minutes'
                );
            }

            // ✅ mark as notified
            $slot->update(['notified' => 1]);
        }

        $this->info('Nearby experts notified successfully.');
    
    }
}
