<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refugee extends Model
{
    use HasFactory;

    protected $table = 'refugees';

    protected $fillable = [
        'name',
        'phone_no',
        'date_of_birth',
        'country_of_origin',
        'host_country',
        'password',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];
}
