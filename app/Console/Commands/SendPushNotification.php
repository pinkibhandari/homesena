<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ServiceLocation;
use App\Models\Notification;
use App\Models\UserDevice;
use App\Services\FirebaseService;

class SendPushNotification extends Command
{
    protected $signature = 'app:send-push-notification';
    protected $description = 'Command description';
    public function handle()
    {
        $firebase = new FirebaseService();

        $notifications = Notification::where('status', 1)
            ->where(function ($query) {
                $query->where(
                    'schedule_type',
                    'instant'
                );
                $query->orWhere(function ($q) {
                    $q->where('schedule_type', 'scheduled')
                        ->whereBetween('scheduled_at', [now(), now()->addMinutes(5)]);
                });
            })
            ->get();
        foreach ($notifications as $notification) {
            try {
                $notification->update(['status' => 'processing']);
                $tokens = [];
                /*
                |--------------------------------------------------------------------------
                | ALL USERS
                |--------------------------------------------------------------------------
                */
                if ($notification->send_type == 'all') {
                    $tokens = UserDevice::whereHas('user', function ($q) use ($notification) {
                        $q->where('role', $notification->role);
                    })
                        ->pluck('token')
                        ->toArray();
                }
                /*
                |--------------------------------------------------------------------------
                | SINGLE USER
                |--------------------------------------------------------------------------
                */
                // elseif ($notification->send_type == 'single') {
                //     $tokens = UserDevice::where('user_id', $notification->user_id)
                //         ->whereHas('user', function ($q) use ($notification) {
                //             $q->where('role', $notification->role);
                //         })
                //         ->pluck('token')
                //         ->toArray();
                // }

                /*
                |--------------------------------------------------------------------------
                | LOCATION USERS
                |--------------------------------------------------------------------------
                */ elseif ($notification->send_type == 'location') {
                    $location = ServiceLocation::find($notification->location_id);
                    if (!$location) {
                        continue;
                    }
                    $userIds = User::where('users.role', $notification->role)
                        ->where('users.status', 1)
                        ->join('addresses', 'addresses.user_id', '=', 'users.id')
                        ->select('users.id')
                        ->selectRaw(
                            "(6371 * acos(
                                cos(radians(?)) *
                                cos(radians(addresses.address_lat)) *
                                cos(radians(addresses.address_long) - radians(?)) +
                                sin(radians(?)) *
                                sin(radians(addresses.address_lat))
                               )) AS distance",
                            [
                                $location->latitude,
                                $location->longitude,
                                $location->latitude
                            ]
                        )
                        ->whereNotNull('addresses.address_lat')
                        ->whereNotNull('addresses.address_long')
                        ->having('distance', '<=', 2)
                        ->pluck('users.id');

                    $tokens = UserDevice::whereIn('user_id', $userIds)
                        ->pluck('token')
                        ->toArray();
                }
                $tokens = array_values(array_unique(array_filter($tokens)));
                /*
                |--------------------------------------------------------------------------
                | SEND PUSH
                |--------------------------------------------------------------------------
                */

                if (count($tokens)) {
                    // Send in chunks (FCM limit safe)
                    foreach (array_chunk($tokens, 500) as $tokenChunk) {
                        $firebase->sendBulkNotification(
                            $tokenChunk,
                            $notification->title,
                            $notification->message,
                            [
                                'notification_id' => (string) $notification->id
                            ]
                        );
                    }
                }
                /*
                |--------------------------------------------------------------------------
                | UPDATE STATUS
                |--------------------------------------------------------------------------
                */
                $notification->update([
                    // 'status' => 'sent',
                    'is_sent' => 1,
                    'sent_at' => now()
                ]);

            } catch (\Exception $e) {
                $notification->update([
                    // 'status' => 'failed'
                ]);
            }
        }

        return true;
    }
}
