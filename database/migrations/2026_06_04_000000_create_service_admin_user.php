<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateServiceAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = User::updateOrCreate(
            ['email' => 'serviceadmin@gmail.com'],
            [
                'first_name' => 'Service',
                'last_name' => 'Admin',
                'password' => '$2y$10$to4ehBQEVJqM5qvwZBeAM.Kg0tyxtV2szypyRtcSyKSpCbzfkXyzC',
                'plain_password' => '123456789',
                'position' => 'Service Admin',
                'branch' => 'All Branches',
                'is_super_admin' => true,
                'status' => true,
            ]
        );

        // Also assign to Animal Bite Center division
        $user->divisions()->updateOrCreate(['division_name' => 'Animal Bite Center']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $user = User::where('email', 'serviceadmin@gmail.com')->first();
        if ($user) {
            $user->divisions()->delete();
            $user->forceDelete();
        }
    }
}
