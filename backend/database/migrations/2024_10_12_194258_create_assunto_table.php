<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssuntoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('assunto', function (Blueprint $table) {
            $table->id('codAs');  // Chave primária
            $table->string('Descricao', 40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('assunto');
    }
}
