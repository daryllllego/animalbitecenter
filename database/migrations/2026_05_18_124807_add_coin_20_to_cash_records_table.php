<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoin20ToCashRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_records', function (Blueprint $table) {
            $table->integer('coin_20')->default(0)->after('denom_20');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_records', function (Blueprint $table) {
            $table->dropColumn('coin_20');
        });
    }
}
