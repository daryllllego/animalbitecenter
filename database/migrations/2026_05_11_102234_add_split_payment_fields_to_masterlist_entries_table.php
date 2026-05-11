<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSplitPaymentFieldsToMasterlistEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->boolean('is_split_payment')->default(false)->after('payment_method');
            $table->decimal('cash_amount', 10, 2)->nullable()->after('is_split_payment');
            $table->decimal('online_amount', 10, 2)->nullable()->after('cash_amount');
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
            $table->dropColumn(['is_split_payment', 'cash_amount', 'online_amount']);
        });
    }
}
