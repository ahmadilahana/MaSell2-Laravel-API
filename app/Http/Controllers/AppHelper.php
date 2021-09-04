<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\EmailVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;


class AppHelper extends Controller
{
    public static function getLocationInfoByIp(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        $result  = array('countryName' => '','countryCode'=>'', 'region'=>'');
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
        
        if($ip_data && $ip_data->geoplugin_countryName != null){
            $result['countryName'] = $ip_data->geoplugin_countryName;
            $result['countryCode'] = $ip_data->geoplugin_countryCode;
            $result['region'] = $ip_data->geoplugin_regionName;
        }

       return $result;
    }

    public static function sendEmail($email, $code){
        $emailVerifycation = new EmailVerification();

        $emailVerifycation->verifyCode = $code;

        Mail::to($email)->send($emailVerifycation);
    }
}
