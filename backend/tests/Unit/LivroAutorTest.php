<?php

namespace Tests\Unit;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\LivroAutor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroAutorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_livro_autor()
    {
        // Criar um livro
        $livro = Livro::create([
            'Titulo' => 'Exemplo de Livro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2023',
        ]);

        // Criar um autor
        $autor = Autor::create([
            'Nome' => 'Autor Exemplo',
        ]);

        // Criar um relacionamento entre livro e autor
        $livroAutor = LivroAutor::create([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        // Verificações
        $this->assertNotNull($livroAutor);
        $this->assertEquals($livro->Codl, $livroAutor->Livro_Codl);
        $this->assertEquals($autor->CodAu, $livroAutor->Autor_CodAu);
    }

    /** @test */
    public function relacionamento_com_livro()
    {
        $livro = Livro::create([
            'Titulo' => 'Exemplo de Livro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2023',
        ]);

        $autor = Autor::create([
            'Nome' => 'Autor Exemplo',
        ]);

        $livroAutor = LivroAutor::create([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        // Verificar se o relacionamento com Livro funciona
        $this->assertInstanceOf(Livro::class, $livroAutor->livro);
        $this->assertEquals($livro->Codl, $livroAutor->livro->Codl);
    }

    /** @test */
    public function relacionamento_com_autor()
    {
        $livro = Livro::create([
            'Titulo' => 'Exemplo de Livro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2023',
        ]);

        $autor = Autor::create([
            'Nome' => 'Autor Exemplo',
        ]);

        $livroAutor = LivroAutor::create([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        // Verificar se o relacionamento com Autor funciona
        $this->assertInstanceOf(Autor::class, $livroAutor->autor);
        $this->assertEquals($autor->CodAu, $livroAutor->autor->CodAu);
    }

    /** @test */
    public function nao_pode_criar_livroautor_sem_livro()
    {
        $autor = Autor::create([
            'Nome' => 'Autor Exemplo',
        ]);

        // Tentar criar livro_autor sem um livro deve falhar
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        LivroAutor::create([
            'Livro_Codl' => null,  // Livro não existe
            'Autor_CodAu' => $autor->CodAu,
        ]);
    }

    /** @test */
    public function nao_pode_criar_livroautor_sem_autor()
    {
        $livro = Livro::create([
            'Titulo' => 'Exemplo de Livro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2023',
        ]);

        // Tentar criar livro_autor sem um autor deve falhar
        $this->expectException(\Illuminate\Database\QueryException::class);

        LivroAutor::create([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => null,  // Autor não existe
        ]);
    }
}
