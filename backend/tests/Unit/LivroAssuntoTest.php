<?php

namespace Tests\Unit;

use App\Models\Livro;
use App\Models\Assunto;
use App\Models\LivroAssunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroAssuntoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_livro_assunto()
    {
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $assunto = Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        $livroAssunto = LivroAssunto::create([
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ]);

        $this->assertNotNull($livroAssunto);
        $this->assertEquals($livro->Codl, $livroAssunto->Livro_codl);
        $this->assertEquals($assunto->codAs, $livroAssunto->Assunto_codAs);
    }

    /** @test */
    public function relacionamento_com_livro()
    {
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $assunto = Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        $livroAssunto = LivroAssunto::create([
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ]);
        
        // Verificar se o relacionamento com Livro funciona
        $this->assertInstanceOf(Livro::class, $livroAssunto->livro);
        $this->assertEquals($livro->Codl, $livroAssunto->livro->Codl);
    }

    /** @test */
    public function relacionamento_com_assunto()
    {
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $assunto = Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        $livroAssunto = LivroAssunto::create([
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ]);

        // Verificar se o relacionamento com Assunto funciona
        $this->assertInstanceOf(Assunto::class, $livroAssunto->assunto);
        $this->assertEquals($assunto->codAs, $livroAssunto->assunto->codAs);
    }

    /** @test */
    public function nao_pode_criar_livroassunto_sem_assunto()
    {
        $livro = Livro::create([
            'Titulo' => 'Duna',
            'Editora' => 'Companhia das Letras',
            'Edicao' => 1,
            'AnoPublicacao' => '1965',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        LivroAssunto::create([
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => null,
        ]);
    }

    /** @test */
    public function nao_pode_criar_livroassunto_sem_livro()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Ficção Científica',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        LivroAssunto::create([
            'Livro_codl' => null,
            'Assunto_codAs' => $assunto->codAs,
        ]);
    }
}
