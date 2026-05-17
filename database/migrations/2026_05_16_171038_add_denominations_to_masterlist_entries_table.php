<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDenominationsToMasterlistEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->integer('denom_1000')->default(0)->after('online_payment_method');
            $table->integer('denom_500')->default(0)->after('denom_1000');
            $table->integer('denom_200')->default(0)->after('denom_500');
            $table->integer('denom_100')->default(0)->after('denom_200');
            $table->integer('denom_50')->default(0)->after('denom_100');
            $table->integer('denom_20')->default(0)->after('denom_50');
            $table->integer('coin_20')->default(0)->after('denom_20');
            $table->integer('coin_10')->default(0)->after('coin_20');
            $table->integer('coin_5')->default(0)->after('coin_10');
            $table->integer('coin_1')->default(0)->after('coin_5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->dropColumn([
                'denom_1000', 'denom_500', 'denom_200', 'denom_100', 'denom_50', 
                'denom_20', 'coin_20', 'coin_10', 'coin_5', 'coin_1'
            ]);
        });
    }
}
