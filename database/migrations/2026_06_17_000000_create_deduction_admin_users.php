<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        $password = '$2y$10$to4ehBQEVJqM5qvwZBeAM.Kg0tyxtV2szypyRtcSyKSpCbzfkXyzC'; // 123456789
        
        $cebuAdmin = User::updateOrCreate(
            ['email' => 'admincebu@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Cebu',
                'password' => $password,
                'plain_password' => '123456789',
                'position' => 'Deduction Admin',
                'branch' => 'Cebu',
                'is_super_admin' => false,
                'status' => true,
            ]
        );
        $cebuAdmin->divisions()->updateOrCreate(['division_name' => 'Animal Bite Center']);

        $boholAdmin = User::updateOrCreate(
            ['email' => 'adminbohol@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Bohol',
                'password' => $password,
                'plain_password' => '123456789',
                'position' => 'Deduction Admin',
                'branch' => 'Bohol',
                'is_super_admin' => false,
                'status' => true,
            ]
        );
        $boholAdmin->divisions()->updateOrCreate(['division_name' => 'Animal Bite Center']);
    }

    public function down()
    {
        $cebu = User::where('email', 'admincebu@gmail.com')->first();
        if ($cebu) {
            $cebu->divisions()->delete();
            $cebu->forceDelete();
        }

        $bohol = User::where('email', 'adminbohol@gmail.com')->first();
        if ($bohol) {
            $bohol->divisions()->delete();
            $bohol->forceDelete();
        }
    }
};
