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
        DB::statement("DROP VIEW IF EXISTS view_relatorio_livros");

        DB::statement("CREATE VIEW view_relatorio_livros AS
                SELECT 
                    l.Titulo AS titulo_livro,
                    l.AnoPublicacao,
                    l.Editora,
                    l.preco,
                    IFNULL(GROUP_CONCAT(DISTINCT s.Descricao ORDER BY s.Descricao ASC), '') AS assuntos,
                    IFNULL(GROUP_CONCAT(DISTINCT a.Nome ORDER BY a.Nome ASC), '') AS autores,
                    IFNULL(GROUP_CONCAT(DISTINCT s.CodAs ORDER BY s.CodAs ASC), '') AS Assunto_codAs,
                    IFNULL(GROUP_CONCAT(DISTINCT a.CodAu ORDER BY a.CodAu ASC), '') AS Autor_CodAu
                FROM
                    livro l
                    LEFT JOIN livro_autor la ON l.Codl = la.Livro_Codl
                    LEFT JOIN autor a ON la.Autor_CodAu = a.CodAu
                    LEFT JOIN livro_assunto las ON l.Codl = las.Livro_Codl
                    LEFT JOIN assunto s ON las.Assunto_codAs = s.CodAs
                GROUP BY l.Codl
                ORDER BY l.Titulo;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_relatorio_livros");
    }
};
