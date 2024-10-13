<?php

namespace Tests\Unit;

use App\DAO\LivroAutorDAO;
use App\Models\Autor;
use App\Models\Livro;
use App\Models\LivroAutor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroAutorDAOTest extends TestCase
{
    use RefreshDatabase;

    private LivroAutorDAO $livroAutorDAO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livroAutorDAO = new LivroAutorDAO();
    }

    /** @test */
    public function pode_criar_livro_autor()
    {
        // Criação de um Livro e um Autor
        $livro = Livro::create([
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $autor = Autor::create([
            'Nome' => 'J.R.R. Tolkien',
        ]);

        // Criação do LivroAutor
        $livroAutor = $this->livroAutorDAO->criar([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        $this->assertInstanceOf(LivroAutor::class, $livroAutor);
        $this->assertEquals($livro->Codl, $livroAutor->Livro_Codl);
        $this->assertEquals($autor->CodAu, $livroAutor->Autor_CodAu);
    }

    /** @test */
    public function pode_buscar_livro_autor_por_id()
    {
        // Criação de um Livro e um Autor
        $livro = Livro::create([
            'Titulo' => '1984',
            'Editora' => 'Companhia das Letras',
            'Edicao' => 1,
            'AnoPublicacao' => '1949',
        ]);

        $autor = Autor::create([
            'Nome' => 'George Orwell',
        ]);

        // Criação do LivroAutor
        $this->livroAutorDAO->criar([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        // Busca do LivroAutor
        $livroAutor = $this->livroAutorDAO->buscarPorId($livro->Codl, $autor->CodAu);

        $this->assertInstanceOf(LivroAutor::class, $livroAutor);
        $this->assertEquals($livro->Codl, $livroAutor->Livro_Codl);
        $this->assertEquals($autor->CodAu, $livroAutor->Autor_CodAu);
    }

    /** @test */
    public function pode_atualizar_livro_autor()
    {
        // Criar um livro
        $livro = Livro::create([
            'Titulo' => 'O Hobbit',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1937',
        ]);

        // Criar um autor
        $autor = Autor::create([
            'CodAu' => 2,
            'Nome' => 'J.R.R. Tolkien',
        ]);

        // Criar uma relação LivroAutor
        $livroAutor = $this->livroAutorDAO->criar([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        // Atualizar a relação
        $updatedData = [
            'Autor_CodAu' => $autor->CodAu, // Use o id do autor que foi criado
            // Adicione outros campos que você possa ter
        ];
        $this->livroAutorDAO->atualizar($livro->Codl, $autor->CodAu, $updatedData);

        // Buscar o LivroAutor atualizado
        $livroAutorAtualizado = $this->livroAutorDAO->buscarPorId($livro->Codl, $autor->CodAu);
        
        // Verificar se a atualização foi bem-sucedida
        $this->assertEquals($autor->CodAu, $livroAutorAtualizado->Autor_CodAu);
    }

    /** @test */
    public function pode_deletar_livro_autor()
    {
        // Criação de um Livro e um Autor
        $livro = Livro::create([
            'Titulo' => 'O Hobbit',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1937',
        ]);

        $autor = Autor::create([
            'Nome' => 'J.R.R. Tolkien',
        ]);

        // Criação do LivroAutor
        $this->livroAutorDAO->criar([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        // Deleção do LivroAutor
        $resultado = $this->livroAutorDAO->deletar($livro->Codl, $autor->CodAu);
        $this->assertTrue($resultado);
    }

    /** @test */
    public function nao_pode_buscar_livro_autor_inexistente()
    {
        // Espera que uma ModelNotFoundException seja lançada
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->livroAutorDAO->buscarPorId(999, 999); // IDs que não existem
    }

    /** @test */
    public function nao_pode_atualizar_livro_autor_inexistente()
    {
        // Espera que uma ModelNotFoundException seja lançada
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->livroAutorDAO->atualizar(999, 999, [
            'Autor_CodAu' => 2, // Supondo que o Autor com CodAu 2 exista
        ]);
    }

    /** @test */
    public function nao_pode_deletar_livro_autor_inexistente()
    {
        $livroCodlInexistente = 999; // Um Codl que não existe
        $autorCodAuInexistente = 999; // Um CodAu que não existe

        $resultado = $this->livroAutorDAO->deletar($livroCodlInexistente, $autorCodAuInexistente);
        $this->assertFalse($resultado);
    }
}
