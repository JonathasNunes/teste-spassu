<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutorTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('autor', function (Blueprint $table) {
            $table->id('CodAu');  // Chave primária
            $table->string('Nome', 40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('autor');
    }
}

