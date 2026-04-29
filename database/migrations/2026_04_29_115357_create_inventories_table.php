<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('shift', ['opening', 'closing', 'endorsement']);
            $table->timestamps();

            $table->unique(['date', 'shift']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
