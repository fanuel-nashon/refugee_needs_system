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
        //leading african countries in refugee emigration in Africa that will be used as origin countries for refugees in the system
        $countries = [
            ['name' => 'Ethiopia'],
            ['name' => 'Sudan'],
            ['name' => 'Somalia'],
            ['name' => 'Democratic Republic of Congo'],
            ['name' => 'Central African Republic'],
            ['name' => 'South Sudan'],
            ['name' => 'Burundi'],
            ['name' => 'Niger'],
            ['name' => 'Mali'],
            ['name' => 'Chad'],

            // adding countries that are leading in refugee resettlement in Africa that will be used as destination countries for refugees in the system
            ['name' => 'South Africa'],
            ['name' => 'Tanzania'],
            ['name' => 'Uganda'],
            ['name' => 'Kenya'],
            ['name' => 'Rwanda'],
            ['name' => 'Ghana'],
            ['name' => 'Senegal'],
            ['name' => 'Ivory Coast'],
            ['name' => 'Zambia'],
            ['name' => 'Zimbabwe'],
        ];

        //creating them into the database using Country model

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
