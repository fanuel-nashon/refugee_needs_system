<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'aid_worker']);

        // Default admin account — change password after first login
        $admin = User::firstOrCreate(
            ['email' => 'admin@refugeesystem.local'],
            [
                'name'     => 'System Administrator',
                'password' => 'Admin@1234',
            ]
        );
        $admin->assignRole('admin');
    }
}
