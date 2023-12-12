<?php
namespace App\Helpers;

class SmsHern
{
    public static function sendSms($message, $to) {
        $to = '+63' . ltrim($to, '0'); 
        $url = 'https://smshern.codize.dev/api/sms/send';
        $data = array("apikey" => config('sms.smshern_api_key'),  "mobile_number" => $to,  "message" => $message);
        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
    }
}