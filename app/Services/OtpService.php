<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class OtpService
{
    //generating random OTP
    public function generate(string $phone): int
    {
        $otp = rand(100000, 999999);
        Cache::put("otp_{$phone}", hash('sha256', $otp), now()->addMinutes(5)); //expires in 5 mins
        return $otp;
    }
    //send otp via sms
    public function send(string $phone, int $otp): void
    {
        //api call to send sms
        Http::post(config('services.sms.test_url'), [
            'to' => $phone,
            'message' => "Your verification code is {$otp}"
        ]);
    }

    //verifying the opt entered by the user
    public function verify(string $phone, int $inputOtp): bool
    {
        $cachedOtp=Cache::get("otp_{$phone}");

        if(!$cachedOtp){
            return false;
        }

        //check if cached otp is same as the hashed one
        $isValid=hash('sha256', $inputOtp) === $cachedOtp;

        if($isValid){
            Cache::forget("otp_{$phone}"); //forget the otp
        }
        
        return $isValid;
    }
}