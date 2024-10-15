<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $value = env('APP_ENV', 'local');
        
        if ($value != 'testing') {
            $this->down();

            DB::statement("CREATE VIEW view_relatorio_livros AS
                SELECT 
                    a.Nome AS nome_autor,
                    l.Titulo AS titulo_livro,
                    l.AnoPublicacao,
                    l.Editora,
                    l.preco,
                    GROUP_CONCAT(DISTINCT s.Descricao ORDER BY s.Descricao ASC) AS assuntos
                FROM
                    autor a
                    LEFT JOIN livro_autor la ON a.CodAu = la.Autor_CodAu
                    LEFT JOIN livro l ON la.Livro_Codl = l.Codl
                    LEFT JOIN livro_assunto las ON l.Codl = las.Livro_Codl
                    LEFT JOIN assunto s ON las.Assunto_codAs = s.CodAs
                GROUP BY a.CodAu, l.Codl
                ORDER BY a.Nome, l.Titulo;");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $value = env('APP_ENV', 'local');
        if ($value != 'testing') {
            DB::statement("DROP VIEW IF EXISTS view_relatorio_livros");
        }
    }
};
