<?php
namespace App\Services; 
use Illuminate\Support\Facades\Http;

class FirebaseService
{
   
    public function sendNotification($token,$title,$body)
    {
        $serverKey = env('FIREBASE_SERVER_KEY');

        $data = [
            "to"=>$token,
            "notification"=>[
                "title"=>$title,
                "body"=>$body
            ]
        ];

        $headers = [
            "Authorization: key=".$serverKey,
            "Content-Type: application/json"
        ];

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,"https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));

        $response=curl_exec($ch);

        curl_close($ch);

        return $response;
    }


}