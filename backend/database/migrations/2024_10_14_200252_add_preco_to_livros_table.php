<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('livro', function (Blueprint $table) {
            $table->decimal('preco', 8, 2)->nullable()->after('AnoPublicacao'); // Define o campo 'preco' como decimal, opcional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livro', function (Blueprint $table) {
            $table->dropColumn('preco');
        });
    }
};
