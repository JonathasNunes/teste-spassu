<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivroTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('livro', function (Blueprint $table) {
            $table->id('Codl');  // Chave primária
            $table->string('Titulo', 40);
            $table->string('Editora', 40);
            $table->integer('Edicao');
            $table->string('AnoPublicacao', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('livro');
    }
}
