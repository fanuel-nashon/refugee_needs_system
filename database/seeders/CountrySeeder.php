<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        //seeding some countries to the database (picked them from the list of top 10 African countries with the highest number of refugees)
        $countries = [
            ['name' => 'Ethiopia'],
            ['name' => 'Uganda'],
            ['name' => 'Sudan'],
            ['name' => 'Kenya'],
            ['name' => 'DR Congo'],
            ['name' => 'Chad'],
            ['name' => 'Libya'],
            ['name' => 'Somalia'],
            ['name' => 'Niger'],
            ['name' => 'Cameroon'],
        ];

        //creating them into the database using Country model

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
