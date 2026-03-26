<?php
namespace App\Services; 

use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected function getAccessToken()
    {
        $credentials = json_decode(file_get_contents(storage_path('app/firebase.json')), true);

        $client = new \Google_Client();
        $client->setAuthConfig($credentials);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'];
    }

    public function send($fcmToken, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();
        $projectId = json_decode(file_get_contents(storage_path('app/firebase.json')), true)['project_id'];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $response = Http::withToken($accessToken)->post($url, [
            "message" => [
                "token" => $fcmToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body
                ],
                "data" => $data
            ]
        ]);

        \Log::info('FCM', $response->json());
    }

}