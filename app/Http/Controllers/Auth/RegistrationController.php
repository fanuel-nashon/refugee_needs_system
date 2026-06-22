<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Refugee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    //function that picks countries from the database and renders to the blade
    public function countries()
    {
        $countries=cache()->remember('countries_list', 86400, function(){
            return DB::table('countries')->pluck('name');
        });

        //Fallback: if cache and db both fail. 
        if($countries->isEmpty()) {
            return DB::table('countries')->pluck('name');
        }

        return response()->json($countries);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        try{
            $validator=Validator::make($request->all(),[
                'name'=>'required|string',
                'phone_no'=>'required|string|unique:refugees,phone_no',
                    'date_of_birth' => [
                        'required',
                        'date',
                        'before_or_equal:' . now()->subYears(18)->toDateString()
                    ],
                'country_of_origin' => 'required|string|exists:countries,name',
                'host_country'=>'required|string|exists:countries,name|different:country_of_origin',
            ]);

            if($validator->fails()){
                return response()->json(
                    $validator->errors(),422);
            }
      
            Refugee::create($validator->validated());
            return response()->json([
                'status'=>'success',
            ], 201);

        }catch (\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>'Registration failed. Please try again',
            ], 500);
        }     
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
