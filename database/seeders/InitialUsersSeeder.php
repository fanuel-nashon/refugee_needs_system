<?php

namespace Database\Seeders;

use App\Models\Refugee;
use App\Models\User;
use Illuminate\Database\Seeder;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        // ── Staff accounts ───────────────────────────────────────────────────

        $admin = User::firstOrCreate(
            ['email' => 'admin@refugeesystem.local'],
            [
                'name'     => 'System Administrator',
                'password' => 'Admin@1234',
            ]
        );
        $admin->syncRoles(['admin']);

        $aidWorker = User::firstOrCreate(
            ['email' => 'aidworker@refugeesystem.local'],
            [
                'name'     => 'Aid Worker Demo',
                'password' => 'Admin@1234',
            ]
        );
        $aidWorker->syncRoles(['aid_worker']);

        // ── Demo refugee account ─────────────────────────────────────────────

        Refugee::firstOrCreate(
            ['phone_no' => '+255621090909'],
            [
                'name'              => 'Genet Kabede',
                'date_of_birth'     => '2000-05-13',
                'country_of_origin' => 'Ethiopia',
                'host_country'      => 'Tanzania',
                'password'          => 'Password@1234',
            ]
        );
    }
}
