<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $credentials;

    public function __construct()
    {
        $this->credentials = json_decode(
            file_get_contents(storage_path('app/firebase/firebase_credentials.json')),
            true
        );
    }

    /**
     * Generate Access Token (cached)
     */
    public function getAccessToken()
    {
        return Cache::remember('firebase_access_token', 3500, function () {

            $now = time();

            $payload = [
                "iss" => $this->credentials['client_email'],
                "sub" => $this->credentials['client_email'],
                "aud" => $this->credentials['token_uri'],
                "iat" => $now,
                "exp" => $now + 3600,
                "scope" => "https://www.googleapis.com/auth/firebase.messaging"
            ];

            $jwt = JWT::encode($payload, $this->credentials['private_key'], 'RS256');

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->credentials['token_uri']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]));

            $response = json_decode(curl_exec($ch), true);

            curl_close($ch);

            return $response['access_token'] ?? null;
        });
    }

    /**
     * Send Notification
     */
    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                return ['success' => false, 'error' => 'Access token failed'];
            }

            $url = "https://fcm.googleapis.com/v1/projects/" . env('FIREBASE_PROJECT_ID') . "/messages:send";

            $payload = [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body
                    ],
                    "data" => $data,
                     "android" => [
                       "priority" => "high"
                      ]
                ]
            ];

            $headers = [
                "Authorization: Bearer " . $accessToken,
                "Content-Type: application/json"
            ];

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
            ]);

            $response = curl_exec($ch);
            $error = curl_error($ch);

            curl_close($ch);

            if ($error) {
                return ['success' => false, 'error' => $error];
            }

            return [
                'success' => true,
                'response' => json_decode($response, true)
            ];

        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send Bulk Notification
     */
    public function sendBulkNotification($tokens, $title, $body, $data = [])
    {
        $results = [];

        $tokens = array_values(array_unique(array_filter($tokens)));

        // split into chunks (safe for cron + memory)
        foreach (array_chunk($tokens, 20) as $chunk) {

            foreach ($chunk as $token) {

                $results[] = $this->sendNotification(
                    $token,
                    $title,
                    $body,
                    $data
                );
            }
        }

        return $results;
    }
}