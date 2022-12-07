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
  function fireCURL($paypalCredentials, $postField, $header)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $paypalCredentials['url'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $paypalCredentials['method'],
      CURLOPT_POSTFIELDS => $postField,
      CURLOPT_HTTPHEADER => $header
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return (object) json_decode($response, true);
  }
  function fireCURLTwo($paypalCredentials)
  {
    // dd($paypalCredentials['PayPal-Request-Id']); 

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/catalogs/products',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $paypalCredentials['postField'],
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'PayPal-Request-Id:' . $paypalCredentials['PayPal-Request-Id'],
        'Authorization: Bearer ' . $paypalCredentials['Authentication']
      ),
    ));
    $response = curl_exec($curl);
    dd($response);
    curl_close($curl);
    return (object) json_decode($response, true);
  }
}
