<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    //Auto-invalidate of cache keys when countries change
    protected static function booted()
    {
        static::created(function () {
            cache()->forget('countries_list');
        });

        static::updated(function () {
            cache()->forget('countries_list');
        });

        static::deleted(function () {
            cache()->forget('countries_list');
        });
    }

}
