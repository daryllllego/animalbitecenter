<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceNumberToMasterlistEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('payment_method');
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
            $table->dropColumn('reference_number');
        });
    }
}
