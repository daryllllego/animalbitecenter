<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountFieldsToMasterlistEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masterlist_entries', function (Blueprint $table) {
            $table->boolean('is_discounted')->default(false)->after('amount_paid');
            $table->string('discount_type')->nullable()->after('is_discounted');
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('discount_type');
            $table->decimal('original_amount', 10, 2)->nullable()->after('discount_percentage');
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
            $table->dropColumn(['is_discounted', 'discount_type', 'discount_percentage', 'original_amount']);
        });
    }
}
