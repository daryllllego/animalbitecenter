<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('position');
            $table->boolean('is_super_admin')->default(false)->after('status');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('id');
        });

        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('id');
        });

        Schema::table('deductions', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('id');
        });

        Schema::table('daily_records', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('id');
        });

        Schema::table('cash_records', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('id');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['branch', 'is_super_admin']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('branch');
        });

        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->dropColumn('branch');
        });

        Schema::table('deductions', function (Blueprint $table) {
            $table->dropColumn('branch');
        });

        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropColumn('branch');
        });

        Schema::table('cash_records', function (Blueprint $table) {
            $table->dropColumn('branch');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('branch');
        });
    }
}
