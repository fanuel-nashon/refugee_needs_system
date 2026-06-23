<?php

namespace Database\Seeders;

use App\Models\Refugee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefugeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Refugee::firstOrCreate(
            ['phone_no' => '+255621090909'],
            [
                'name'             => 'Genet Kabede',
                'date_of_birth'    => '2008-05-13',
                'country_of_origin'=> 'Ethiopia',
                'host_country'     => 'Tanzania',
                'password'         => 'Password@1234',
            ]
        );
    }
}
