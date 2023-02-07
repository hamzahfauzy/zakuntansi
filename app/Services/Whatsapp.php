<?php
namespace App\Services;

class Whatsapp
{
    function send($number, $message)
    {
        $data = [
            'api_key' => env('WA_APIKEY'),
            'sender'  => env('WA_SENDER'),
            'number'  => $number,
            'message' => $message
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://wa.khazanahilmu.sch.id/app/api/send-message",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($data))
        );
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
                        
    }
}