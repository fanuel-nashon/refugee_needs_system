<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Refugee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone_no' => 'required|string',
            'password' => 'required|string',
        ]);

        $refugee = Refugee::where('phone_no', $request->phone_no)->first();

        if (!$refugee || !Hash::check($request->password, $refugee->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid phone number or password.',
            ], 401);
        }

        session([
            'refugee_id'    => $refugee->id,
            'refugee_phone' => $refugee->phone_no,
            'refugee_name'  => $refugee->name,
        ]);

        return response()->json(['status' => 'success']);
    }
}
