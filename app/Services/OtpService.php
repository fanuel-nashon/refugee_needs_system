<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OtpService
{
    protected string $apiToken;
    protected string $smsUrl;

    public function __construct()
    {
        $this->apiToken = config('services.sms.token');
        $this->smsUrl   = config('services.sms.test_url');
    }

    public function generate(string $phone): int
    {
        $otp = rand(100000, 999999);
        Cache::put("otp_{$phone}", hash('sha256', $otp), now()->addMinutes(5));
        return $otp;
    }

    public function send(string $phone, int $otp): void
    {
        $response = Http::withHeaders([
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->post($this->smsUrl, [
            'to'      => $phone,
            'message' => "Your verification code is {$otp}",
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Failed to send verification code. Please try again.');
        }
    }

    public function verify(string $phone, int $inputOtp): bool
    {
        $cachedOtp = Cache::get("otp_{$phone}");

        if (!$cachedOtp) {
            return false;
        }

        $isValid = hash('sha256', $inputOtp) === $cachedOtp;

        if ($isValid) {
            Cache::forget("otp_{$phone}");
        }

        return $isValid;
    }
}
