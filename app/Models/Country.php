<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;

    protected $table = 'countries';
    
    protected $fillable = [
        'name',
    ];

    //defining relationship with the Camp model
    // This relationship allows a country to have many camps
    public function camps()
    {
        return $this->hasMany(Camp::class);
    }
}
