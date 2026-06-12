<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camp extends Model
{
    /** @use HasFactory<\Database\Factories\CampFactory> */
    use HasFactory;

    protected $fillable =[
        'capacity',
        'name',
        'location',
    ];

    // define the relationship between the Camp model and the Country model
    // This relationship allows a camp to belong to a single country
    public function Country()
    {
        return $this->belongsTo(Country::class);
    }
}
