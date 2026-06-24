<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Refugee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Services\OtpService;

class RegistrationController extends Controller
{
    public function countries()
    {
        $countries = cache()->remember('countries_list', 86400, function () {
            return DB::table('countries')->pluck('name');
        });

        if ($countries->isEmpty()) {
            return response()->json(DB::table('countries')->pluck('name'));
        }

        return response()->json($countries);
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string',
            'phone_no'              => 'required|string|unique:refugees,phone_no',
            'date_of_birth'         => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
            ],
            'country_of_origin'     => 'required|string|exists:countries,name',
            'host_country'          => 'required|string|exists:countries,name|different:country_of_origin',
            'password'              => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $refugee = Refugee::create($validator->validated());

        session([
            'refugee_id'    => $refugee->id,
            'refugee_phone' => $refugee->phone_no,
            'refugee_name'  => $refugee->name,
        ]);

        return response()->json(['status' => 'registered'], 201);
    }

    public function verifyOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'phone_no' => 'required|string',
            'otp'      => 'required|digits:6',
        ]);

        if (!$otpService->verify($request->phone_no, (int) $request->otp)) {
            return response()->json(['status' => 'invalid_otp'], 422);
        }

        $data = Cache::get("pending_registration_{$request->phone_no}");

        if (!$data) {
            return response()->json(['status' => 'expired'], 422);
        }

        $refugee = Refugee::create($data);
        Cache::forget("pending_registration_{$request->phone_no}");

        session([
            'refugee_id'    => $refugee->id,
            'refugee_phone' => $refugee->phone_no,
            'refugee_name'  => $refugee->name,
        ]);

        return response()->json(['status' => 'registered'], 201);
    }

    public function resendOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'phone_no' => 'required|string',
        ]);

        $phone = $request->phone_no;
        $rateLimitKey = 'resend_otp_' . $phone;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return response()->json([
                'status'  => 'too_many_attempts',
                'message' => 'Too many resend attempts. Please wait before trying again.',
            ], 429);
        }

        if (!Cache::has("pending_registration_{$phone}")) {
            return response()->json([
                'status'  => 'no_pending_registration',
                'message' => 'No pending registration found. Please start over.',
            ], 422);
        }

        try {
            RateLimiter::hit($rateLimitKey, 60);
            $otp = $otpService->generate($phone);
            $otpService->send($phone, $otp);

            return response()->json(['status' => 'otp_resent']);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage() ?: 'Failed to resend code. Please try again.',
            ], 500);
        }
    }
}
