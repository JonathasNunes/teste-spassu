<?php

namespace Tests\Unit;

use App\DAO\LivroDAO;
use App\DAO\LivroAutorDAO;
use App\DAO\LivroAssuntoDAO;
use App\DAO\AutorDAO;
use App\DAO\AssuntoDAO;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroDAOTest extends TestCase
{
    use RefreshDatabase;

    protected $livroDAO;
    protected $livroAutorDAO;
    protected $livroAssuntoDAO;
    protected $autorDAO;
    protected $assuntoDAO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livroDAO = new LivroDAO();
        $this->livroAutorDAO = new LivroAutorDAO();
        $this->livroAssuntoDAO = new LivroAssuntoDAO();
        $this->autorDAO = new AutorDAO();
        $this->assuntoDAO = new AssuntoDAO();
    }

    /** @test */
    public function pode_criar_livro()
    {
        $data = [
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
            'preco' => 129.90
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

        $this->expectException(\Exception::class);
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

        $this->expectException(\Exception::class);
        $this->livroDAO->criar($data);
    }

    /** @test */
    public function pode_criar_livro_com_relacoes_sucesso()
    {
        $autor = $this->autorDAO->criar([
            'Nome' => 'J R R Tolkien'
        ]);

        $assunto = $this->assuntoDAO->criar([
            'Descricao' => 'Fantasia'
        ]);

        $data = [
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
            'Autor_CodAu' => [$autor->CodAu],
            'Assunto_codAs' => [$assunto->codAs],
        ];

        $livro = $this->livroDAO->criar($data);

        $this->assertInstanceOf(Livro::class, $livro);
        $this->assertEquals($data['Titulo'], $livro->Titulo);
    }

     /** @test */
     public function erro_ao_criar_livro_sem_autor()
     {
         $data = [
             'Titulo' => 'O Senhor dos Anéis',
             'Editora' => 'HarperCollins',
             'Edicao' => 1,
             'AnoPublicacao' => '1954',
             'preco' => 49.90,
             'Autor_CodAu' => [12] // O autor está faltando
         ];
 
         $this->expectException(\Exception::class);
         $this->livroDAO->criar($data);
     }


     /** @test */
     public function erro_ao_criar_livro_sem_assunto()
     {
         $data = [
             'Titulo' => 'O Senhor dos Anéis',
             'Editora' => 'HarperCollins',
             'Edicao' => 1,
             'AnoPublicacao' => '1954',
             'preco' => 49.90,
             'Assunto_codAs' => [12] // O assunto está faltando
         ];
 
         $this->expectException(\Exception::class);
         $this->livroDAO->criar($data);
     }

     /** @test */
    public function pode_criar_livro_com_varios_autores()
    {
        $autor1 = $this->autorDAO->criar([
            'Nome' => 'J R R Tolkien'
        ]);
        $autor2 = $this->autorDAO->criar([
            'Nome' => 'G R R Martin'
        ]);
        $autor3 = $this->autorDAO->criar([
            'Nome' => 'J K Rowlin'
        ]);

        $assunto = $this->assuntoDAO->criar([
            'Descricao' => 'Fantasia'
        ]);

        $data = [
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
            'Autor_CodAu' => [$autor1->CodAu, $autor2->CodAu, $autor3->CodAu],
            'Assunto_codAs' => [$assunto->codAs],
        ];

        $livro = $this->livroDAO->criar($data);

        $this->assertInstanceOf(Livro::class, $livro);
        $this->assertEquals($data['Titulo'], $livro->Titulo);
    }

    /** @test */
    public function pode_criar_livro_com_varios_assuntos()
    {
        $autor = $this->autorDAO->criar([
            'Nome' => 'J R R Tolkien'
        ]);

        $assunto1 = $this->assuntoDAO->criar([
            'Descricao' => 'Fantasia'
        ]);
        $assunto2 = $this->assuntoDAO->criar([
            'Descricao' => 'Ficção'
        ]);
        $assunto3 = $this->assuntoDAO->criar([
            'Descricao' => 'Drama'
        ]);

        $data = [
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
            'Autor_CodAu' => [$autor->CodAu],
            'Assunto_codAs' => [$assunto1->codAs, $assunto2->codAs, $assunto3->codAs],
        ];

        $livro = $this->livroDAO->criar($data);

        $this->assertInstanceOf(Livro::class, $livro);
        $this->assertEquals($data['Titulo'], $livro->Titulo);
    }
}
