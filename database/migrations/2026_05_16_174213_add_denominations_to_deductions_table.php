<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDenominationsToDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deductions', function (Blueprint $table) {
            $table->integer('denom_1000')->default(0);
            $table->integer('denom_500')->default(0);
            $table->integer('denom_200')->default(0);
            $table->integer('denom_100')->default(0);
            $table->integer('denom_50')->default(0);
            $table->integer('denom_20')->default(0);
            $table->integer('coin_20')->default(0);
            $table->integer('coin_10')->default(0);
            $table->integer('coin_5')->default(0);
            $table->integer('coin_1')->default(0);
        });
    }

    public function down()
    {
        Schema::table('deductions', function (Blueprint $table) {
            $table->dropColumn([
                'denom_1000', 'denom_500', 'denom_200', 'denom_100', 'denom_50', 'denom_20',
                'coin_20', 'coin_10', 'coin_5', 'coin_1'
            ]);
        });
    }
}
