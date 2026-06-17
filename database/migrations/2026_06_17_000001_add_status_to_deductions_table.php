<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deductions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending')->after('amount');
        });
    }

    public function down()
    {
        Schema::table('deductions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
