<?php

namespace Tests\Unit;

use App\DAO\LivroDAO;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroDAOTest extends TestCase
{
    use RefreshDatabase;

    protected $livroDAO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livroDAO = new LivroDAO();
    }

    /** @test */
    public function pode_criar_livro()
    {
        $data = [
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ];

        $livro = $this->livroDAO->criar($data);

        $this->assertInstanceOf(Livro::class, $livro);
        $this->assertEquals($data['Titulo'], $livro->Titulo);
    }

    /** @test */
    public function pode_buscar_livro_por_id()
    {
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $livroBuscado = $this->livroDAO->buscarPorId($livro->Codl);

        $this->assertEquals($livro->Codl, $livroBuscado->Codl);
    }

    /** @test */
    public function pode_atualizar_livro()
    {
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $data = [
            'Titulo' => 'O Hobbit',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1937',
        ];

        $atualizado = $this->livroDAO->atualizar($livro->Codl, $data);
        $livroAtualizado = $this->livroDAO->buscarPorId($livro->Codl);

        $this->assertTrue($atualizado);
        $this->assertEquals($data['Titulo'], $livroAtualizado->Titulo);
    }

    /** @test */
    public function pode_deletar_livro()
    {
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $deletado = $this->livroDAO->deletar($livro->Codl);
        $livroBuscado = $this->livroDAO->buscarPorId($livro->Codl);

        $this->assertTrue($deletado);
        $this->assertNull($livroBuscado);
    }

    /** @test */
    public function pode_listar_livros()
    {
        Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        Livro::create([
            'Titulo' => 'O Hobbit',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1937',
        ]);

        $livros = $this->livroDAO->listar();

        $this->assertCount(2, $livros);
    }
    
    /** @test */
    public function nao_pode_criar_livro_sem_editora()
    {
        $data = [
            'Titulo' => 'O Senhor dos Anéis',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ];

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->livroDAO->criar($data);
    }

    /** @test */
    public function nao_pode_criar_livro_sem_titulo()
    {
        $data = [
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ];

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->livroDAO->criar($data);
    }
}
