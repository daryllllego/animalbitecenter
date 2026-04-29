<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles if they don't exist (Backup in case RoleSeeder isn't run first)
        $adminRole = Role::updateOrCreate(
            ['name' => 'Super Admin'],
            [
                'division' => 'All Divisions',
                'permission_level' => 'OVERRIDE',
                'description' => 'Full access to all system features.',
            ]
        );

        // 2. Create Default Admin User
        $user = User::updateOrCreate(
            ['email' => 'cabc@intracode.com'],
            [
                'first_name' => 'Cebu ABC',
                'last_name' => 'Admin',
                'employee_number' => 'ADM-001',
                'password' => Hash::make('123456789'),
                'plain_password' => '123456789',
                'division' => 'Animal Bite Center',
                'position' => 'Super Admin',
                'status' => true,
            ]
        );

        // 3. Assign to Animal Bite Center
        $user->divisions()->updateOrCreate(['division_name' => 'Animal Bite Center']);
    }
}
