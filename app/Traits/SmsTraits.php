<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait SmsTraits
{
    // API configuration
    protected $apiUrl = "https://api.mobireach.com.bd/";
    protected $userName = "nagadhat"; // Mobireach Username
    protected $password = "Dhaka@0011"; // Mobireach Password
    protected $senderNumber = "8801810198599"; // Mobireach Sender Number (Mostly Non-masking)

    // This function will collect GET Request Url and run operition then finally return the status
    function send($url)
    {
        $response = Http::get($url);
        return $response;
    }
    // This function will send sms
    function sendSingleSms($to, $sms, $senderNumber = null)
    {
        if (is_null($senderNumber)) {
            $senderNumber = $this->senderNumber;
        }
        $getRequestUrl = $this->apiUrl . "SendTextMessage?Username=" . $this->userName . "&Password=" . $this->password . "&From=$senderNumber&To=$to&Message=$sms";
        $result = $this->send($getRequestUrl);
        return 1;
    }
}