<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\BookingSlot;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;

class NotifyNearbyExpertsBeforeSlot extends Command
{
    protected $signature = 'app:notify-nearby-experts-before-slot';
    protected $description = 'Notify nearby experts 30 minutes before slot start';

   
    public function handle()
    {
        $firebase = app(FirebaseService::class);

        $today = now()->toDateString();
        $time = now()->format('H:i:s');
        
        $slots = BookingSlot::with(['booking.address','booking.user'])
            ->where('status', 'confirmed')
            ->whereNull('expert_id')
            ->where('notified', 0)
            ->where(function ($q) use ($today, $time) {
                $q->where('date', '>', $today)
                  ->orWhere(function ($q2) use ($today, $time) {
                      $q2->where('date', $today)
                         ->where('start_time', '>=', $time);
                  });
            })
            ->get();

        foreach ($slots as $slot) {

            if (!$slot->booking || !$slot->booking->address) {
                continue;
            }

            // lock slot
            $updated = BookingSlot::where('id', $slot->id)
                ->where('notified', 0)
                ->update(['notified' => 2]);

            if (!$updated) continue;

            try {

                $date = Carbon::parse($slot->date)->format('Y-m-d');
                $startTime = Carbon::parse($slot->start_time)->format('H:i:s');
                $endTime = Carbon::parse($slot->end_time)->format('H:i:s');

                // get experts
                $experts = User::where('role', 'expert')
                    ->where('status', 1)
                    ->whereHas('expertDetail', fn($q) => $q->where('is_online', true))
                    ->whereDoesntHave('expertSlots', function ($q) use ($date, $startTime, $endTime) {
                        $q->where('date', $date)
                          ->where('status', 'accepted')
                          ->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    })
                    ->with('devices')
                    ->get();

                if ($experts->isEmpty()) {
                    $this->resetSlot($slot->id);
                    continue;
                }

                // collect tokens
                $tokens = [];

                foreach ($experts as $expert) {
                    foreach ($expert->devices as $device) {
                        if (!empty($device->fcm_token)) {
                            $tokens[] = $device->fcm_token;
                        }
                    }
                }

                $tokens = array_values(array_unique(array_filter($tokens)));

                if (empty($tokens)) {
                    $this->resetSlot($slot->id);
                    continue;
                }

                $earning = $slot->price ? $slot->price * 0.5 : 0;
                //   $tokens = ["d-TeVy7pRHCvrxJAHHAG3K:APA91bE33ua9Zu_FDJWJiDCNkTo2rhTY4sJiceRuoWTk-fwZ5vsadXbJVhmUl3zb6u10t66kBtc9TssckGAOR-MwqHM_xBg83tUi_XQKkLg4MVivijaSKXo", "fE6k7WgaQ2Kak72hLvkpzT:APA91bH8AjS26z4TAPVHPFFoBVo16nYN4hBL6twH7AZu0E_O5264vac7wwgV4WOXKub8150XqftZLLHsd1r_aK1iO891hfqqiVFTz0J15Kle0-zrljKMO3U","dIL1wbD-QUWNWa33p9elH4:APA91bHJGrRmCrILaGjL_wl5moLNIUHFnthkkuqcw304-q-t7gvNuR7UcC2Z9gDxSuptV2RtMxOxLGSwBuiw5FEPbHGNtZJpwtrnIW2IcDibptDWusUS1Yo"];
                // send notifications
                foreach ($tokens as $token) {
                    $address = implode(', ', array_filter([
                            $slot->booking->address->flat_no ?? '',
                            $slot->booking->address->address ?? '',
                            $slot->booking->address->area_name ?? '',
                            $slot->booking->address->landmark ?? ''
                        
                        ]));
                        
                        $profileImage = $slot->booking?->user?->profile_image ?? '';
                        $profile_image = $profileImage ? url('public/' . $profileImage) : '';
                     $data =  [
                        'user_name' =>  $slot->booking->user->name ?? '',
                        'profile_image' => $profile_image,
                        'booking_id' => $slot->booking->id  ?? '',
                        'slot_id' =>  $slot->id  ?? '',
                        'date' =>  $date  ?? '',
                        'time' =>  $startTime  ?? '',
                        'earning' =>  $earning  ?? '',
                        'address' => $address  ?? '',
                        'type' => 'BOOKING_REQUEST',
                      ];
                      
                      // convert all to string safely
                        $data = array_map(function ($value) {
                            return (string) $value;
                        }, $data);
                                                
                        $response = $firebase->sendNotification(
                        $token,
                        'New Booking Available',
                        'A booking near you starts in 30 minutes',
                        $data,
                        'expert'
                    );
                    
                    //   Log::info('FCM Response---------', [
                    //     'token' => $token,
                    //     'response' => $response
                    // ]);
                }
                
             

                BookingSlot::where('id', $slot->id)->update([
                    'notified' => 1,
                    'status' => 'notified'
                ]);

                // Log::info("Slot notified: " . $slot->id);

            } catch (\Exception $e) {

                $this->resetSlot($slot->id);

                // Log::error("Slot error: " . $e->getMessage());
            }
        }

        $this->info("Cron executed successfully");
    }

    private function resetSlot($slotId)
    {
        BookingSlot::where('id', $slotId)
            ->where('notified', 2)
            ->update(['notified' => 0]);
    }
    

    /**
     *  Get nearby + available experts
     */
    private function getExperts($date, $startTime, $endTime, $lat, $lng)
    {
        $radiusKm = 1;
        return User::where('role', 'expert')
            ->where('status', 1)
            ->join('addresses', 'addresses.user_id', '=', 'users.id')
            ->join('expert_details', 'expert_details.user_id', '=', 'users.id')
            ->where('expert_details.is_online', true)
            ->with('devices')
            ->select('users.*')
            ->selectRaw(
                "(6371 * acos(
                    cos(radians(?)) *
                    cos(radians(addresses.address_lat)) *
                    cos(radians(addresses.address_long) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(addresses.address_lat))
                )) AS distance",
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance', 'asc')
            ->whereDoesntHave('expertSlots', function ($q) use ($date, $startTime, $endTime) {
                $q->where('date', $date)
                    ->where('status', 'accepted')
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->get();
    }
}