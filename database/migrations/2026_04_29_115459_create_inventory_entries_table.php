<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->string('vaccine_name');
            $table->integer('quantity')->default(0);
            $table->string('received')->nullable(); // Text like "3 cc"
            $table->integer('transferred')->default(0);
            $table->integer('used')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_entries');
    }
}
