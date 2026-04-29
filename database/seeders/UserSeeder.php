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
            ['email' => 'admin@clarentian.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'employee_number' => 'ADM-001',
                'password' => Hash::make('admin123'),
                'plain_password' => 'admin123',
                'division' => 'Marketing Division',
                'position' => 'Super Admin',
                'status' => true,
            ]
        );

        // 3. Assign to Marketing Division (for sidebar logic)
        $user->divisions()->updateOrCreate(['division_name' => 'Marketing Division']);
    }
}
