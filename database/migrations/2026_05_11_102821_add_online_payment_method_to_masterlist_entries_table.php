<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnlinePaymentMethodToMasterlistEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->string('online_payment_method')->nullable()->after('online_amount');
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
            $table->dropColumn('online_payment_method');
        });
    }
}
