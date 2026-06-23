<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected string $apiToken;
    protected string $smsUrl;
    protected bool   $bypass;

    public function __construct()
    {
        $this->apiToken = config('services.sms.token');
        $this->smsUrl   = config('services.sms.test_url');
        $this->bypass   = (bool) config('services.sms.bypass', false);
    }

    public function generate(string $phone): int
    {
        $otp = rand(100000, 999999);
        Cache::put("otp_{$phone}", hash('sha256', $otp), now()->addMinutes(5));

        if ($this->bypass) {
            Cache::put("otp_plain_{$phone}", $otp, now()->addMinutes(5));
        }

        return $otp;
    }

    public function send(string $phone, int $otp): void
    {
        if ($this->bypass) {
            Log::info("[OTP BYPASS] Code for {$phone}: {$otp}");
            return;
        }

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

    /** Returns the plain OTP when bypass mode is on, null otherwise. */
    public function getBypassOtp(string $phone): ?int
    {
        if (!$this->bypass) {
            return null;
        }
        return Cache::get("otp_plain_{$phone}");
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
            Cache::forget("otp_plain_{$phone}");
        }

        return $isValid;
    }
}
