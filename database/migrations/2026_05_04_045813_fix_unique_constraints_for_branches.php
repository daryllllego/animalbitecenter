<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixUniqueConstraintsForBranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropUnique('daily_records_date_unique');
            $table->unique(['date', 'branch']);
        });

        Schema::table('cash_records', function (Blueprint $table) {
            $table->dropUnique(['date', 'shift']);
            $table->unique(['date', 'shift', 'branch']);
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropUnique(['date', 'shift']);
            $table->unique(['date', 'shift', 'branch']);
        });
    }

    public function down()
    {
        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropUnique(['date', 'branch']);
            $table->unique('date');
        });

        Schema::table('cash_records', function (Blueprint $table) {
            $table->dropUnique(['date', 'shift', 'branch']);
            $table->unique(['date', 'shift']);
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropUnique(['date', 'shift', 'branch']);
            $table->unique(['date', 'shift']);
        });
    }
}
