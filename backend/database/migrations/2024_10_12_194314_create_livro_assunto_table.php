<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivroAssuntoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('livro_assunto', function (Blueprint $table) {
            $table->unsignedBigInteger('Livro_codl');  // Chave estrangeira
            $table->unsignedBigInteger('Assunto_codAs'); // Chave estrangeira
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('Livro_codl')->references('Codl')->on('livro')->onDelete('cascade');
            $table->foreign('Assunto_codAs')->references('codAs')->on('assunto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('livro_assunto');
    }
}