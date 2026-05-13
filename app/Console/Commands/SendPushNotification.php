<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\DeviceToken;
use App\Services\FirebaseService;

class SendPushNotification extends Command
{
    protected $signature = 'app:send-push-notification';
    protected $description = 'Command description';
    public function handle()
    {
         $firebase = new FirebaseService();
    }
}
