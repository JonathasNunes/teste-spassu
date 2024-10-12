<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivroAutorTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->unsignedBigInteger('Livro_Codl');  // Chave estrangeira
            $table->unsignedBigInteger('Autor_CodAu'); // Chave estrangeira
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('Livro_Codl')->references('Codl')->on('livro')->onDelete('cascade');
            $table->foreign('Autor_CodAu')->references('CodAu')->on('autor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('livro_autor');
    }
}
