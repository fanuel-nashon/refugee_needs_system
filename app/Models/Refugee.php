<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refugee extends Model
{
    /** @use HasFactory<\Database\Factories\RefugeeFactory> */
    use HasFactory;

    protected $table='refugees';

    protected $fillable=[
        'name',
        'country_of_origin',
        'date_of_birth',
        'host_country'
    ];
}
