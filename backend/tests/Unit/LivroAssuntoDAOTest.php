<?php

namespace Tests\Unit;

use App\DAO\LivroAssuntoDAO;
use App\Models\Assunto;
use App\Models\Livro;
use App\Models\LivroAssunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\QueryException;

class LivroAssuntoDAOTest extends TestCase
{
    use RefreshDatabase;

    protected $livroAssuntoDAO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livroAssuntoDAO = new LivroAssuntoDAO();
    }

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

        $data = [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ];

        $livroAssunto = $this->livroAssuntoDAO->criar($data);

        $this->assertInstanceOf(LivroAssunto::class, $livroAssunto);
        $this->assertEquals($data['Livro_codl'], $livroAssunto->Livro_codl);
        $this->assertEquals($data['Assunto_codAs'], $livroAssunto->Assunto_codAs);
    }

    /** @test */
    public function pode_buscar_livro_assunto_por_id()
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

        // Use os valores corretos ao buscar
        $livroAssuntoBuscado = $this->livroAssuntoDAO->buscarPorId($livro->Codl, $assunto->codAs);

        $this->assertNotNull($livroAssuntoBuscado);
        $this->assertEquals($livroAssunto->Livro_codl, $livroAssuntoBuscado->Livro_codl);
        $this->assertEquals($livroAssunto->Assunto_codAs, $livroAssuntoBuscado->Assunto_codAs);
    }

    /** @test */
    public function pode_atualizar_livro_assunto()
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

        // Dados para atualizar
        $data = [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ];

        // Atualiza o LivroAssunto
        $result = $this->livroAssuntoDAO->atualizar($livro->Codl, $assunto->codAs, $data);

        $this->assertTrue($result);
        
        // Verifica se os dados foram atualizados corretamente
        $livroAssuntoAtualizado = $this->livroAssuntoDAO->buscarPorId($livro->Codl, $assunto->codAs);
        $this->assertEquals($livroAssunto->Livro_codl, $livroAssuntoAtualizado->Livro_codl);
        $this->assertEquals($livroAssunto->Assunto_codAs, $livroAssuntoAtualizado->Assunto_codAs);
    }

    /** @test */
    public function pode_deletar_livro_assunto()
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

        // Deletar o LivroAssunto
        $result = $this->livroAssuntoDAO->deletar($livro->Codl, $assunto->codAs);

        $this->assertTrue($result);

        // Verifica se foi realmente deletado
        $livroAssuntoBuscado = $this->livroAssuntoDAO->buscarPorId($livro->Codl, $assunto->codAs);
        $this->assertNull($livroAssuntoBuscado);
    }

    /** @test */
    public function pode_listar_livro_assuntos()
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

        LivroAssunto::create([
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ]);

        $livroAssuntos = $this->livroAssuntoDAO->listar();

        $this->assertCount(1, $livroAssuntos);
    }

    /** @test */
    public function nao_pode_criar_livro_assunto_sem_livro_e_assunto()
    {
        $data = []; // Nenhum campo

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->livroAssuntoDAO->criar($data);
    }

    /** @test */
    public function nao_pode_criar_livro_assunto_sem_livro()
    {
        // Criação de um Assunto
        $assunto = Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        // Tentativa de criar LivroAssunto sem Livro
        $this->expectException(QueryException::class);

        $this->livroAssuntoDAO->criar([
            'Assunto_codAs' => $assunto->codAs,
        ]);
    }

    /** @test */
    public function nao_pode_criar_livro_assunto_sem_assunto()
    {
        // Criação de um Livro
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        // Tentativa de criar LivroAssunto sem Assunto
        $this->expectException(QueryException::class);

        $this->livroAssuntoDAO->criar([
            'Livro_codl' => $livro->Codl,
        ]);
    }
}
