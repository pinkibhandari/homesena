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
        $now = Carbon::now();
        $targetTime = $now->copy()->addMinutes(30);

        $firebase = new FirebaseService();

        $slots = BookingSlot::with(['booking.address'])
            ->whereBetween('start_time', [
                $targetTime->copy()->startOfMinute(),
                $targetTime->copy()->endOfMinute()
            ])
            ->where('status', 'confirmed')
            ->whereNull('expert_id')
            ->where('notified', 0)
            ->get();

        foreach ($slots as $slot) {

            if (!$slot->booking || !$slot->booking->address) {
                continue;
            }

            //  lock slot (processing = 2)
            $updated = BookingSlot::where('id', $slot->id)
                ->where('notified', 0)
                ->update(['notified' => 2]);

            if (!$updated) {
                continue;
            }

            try {

                $lat = $slot->booking->address->address_lat;
                $lng = $slot->booking->address->address_long;
                $date = Carbon::parse($slot->date)->format('Y-m-d');
                // find experts
                $experts = $this->getExperts(
                    $date,
                    $slot->start_time,
                    $slot->end_time,
                    $lat,
                    $lng,
                );

                // // retry with bigger radius
                // if ($experts->isEmpty()) {
                //     $experts = $this->getExperts(
                //         $date,
                //         $slot->start_time,
                //         $slot->end_time,
                //         $lat,
                //         $lng,
                //     );
                // }

                if ($experts->isEmpty()) {
                    //  reset for retry
                    $this->resetSlot($slot->id);
                    continue;
                }

                //  collect tokens
                $tokens = [];

                foreach ($experts as $expert) {
                    foreach ($expert->devices as $device) {
                        if (!empty($device->fcm_token)) {
                            $tokens[] = $device->fcm_token;
                        }
                    }
                }

                $tokens = array_unique($tokens);

                if (empty($tokens)) {
                    $this->resetSlot($slot->id);
                    continue;
                }

                //  earning (50%)
                $earning = $slot->price ? $slot->price * 0.5 : 0;

                //  send notification
                $firebase->sendNotification(
                    $tokens,
                    'New Booking Available',
                    'A booking near you starts in 30 minutes',
                    [
                        'booking_id' => (string) $slot->booking->id,
                        'booking_code' => $slot->booking->booking_code,
                        'slot_id' => (string) $slot->id,
                        'date' => $date,
                        'time' => $slot->start_time,
                        'duration' => $slot->duration ?? 0,
                        'location' => $slot->booking->address->address ?? '',
                        'earning' => $earning,
                        'type' => 'BOOKING_REQUEST',
                        'actions' => [
                            ['id' => 'ACCEPT', 'title' => 'Accept'],
                            ['id' => 'REJECT', 'title' => 'Reject']
                        ]
                    ]
                );

                //  mark success
                BookingSlot::where('id', $slot->id)
                    ->where('notified', 2)
                    ->update([
                        'notified' => 1,
                        'status' => 'notified'
                    ]);

            } catch (\Exception $e) {

                //  reset for retry
                $this->resetSlot($slot->id);
                Log::error('Notification failed', [
                    'slot_id' => $slot->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info('Command executed. Slots processed: ' . $slots->count());
    }

    /**
     *  Reset slot for retry
     */
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