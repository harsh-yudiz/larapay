<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

        //Curl call for post request.
        function fireCURL($url, $access_token = NULL, $data = NULL, $method = null)
        {
            // dump($url);
            // dump($data);
            // dd($method);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic e3tjbGllbnRfaWR9fTp7e2NsaWVudF9zZWNyZXR9fQ==',
                    "Content-Type:application/json"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return (object) json_decode($response, true);
        }
    
        //Curl call for get request.
        function fireCURLGET($url, $access_token = NULL, $data = NULL)
        {
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $access_token,
                    "Content-Type:application/json"
    
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response, true);
        }
}
