<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('cash_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('shift', ['opening', 'closing']);
            $table->string('nurse_on_duty')->nullable();
            $table->integer('denom_1000')->default(0);
            $table->integer('denom_500')->default(0);
            $table->integer('denom_200')->default(0);
            $table->integer('denom_100')->default(0);
            $table->integer('denom_50')->default(0);
            $table->integer('denom_20')->default(0);
            $table->integer('denom_10')->default(0);
            $table->integer('denom_5')->default(0);
            $table->integer('denom_1')->default(0);
            $table->text('remarks')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['date', 'shift']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_records');
    }
}
