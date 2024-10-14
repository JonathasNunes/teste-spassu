<?php

namespace Tests\Feature;

use App\DAO\AssuntoDAO;
use App\DAO\AutorDAO;
use App\DAO\LivroAssuntoDAO;
use App\DAO\LivroAutorDAO;
use App\DAO\LivroDAO;
use App\Models\Assunto;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LivroControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $livroDAO;
    protected $assuntoDAO;
    protected $livroAssuntoDAO;
    protected $autorDAO;
    protected $livroAutorDAO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livroDAO = new LivroDAO();
        $this->assuntoDAO = new AssuntoDAO();
        $this->livroAssuntoDAO = new LivroAssuntoDAO();
        $this->autorDAO = new AutorDAO();
        $this->livroAutorDAO = new LivroAutorDAO();
    }

    /** @test */
    public function deve_criar_livro_com_sucesso()
    {
        // Dados válidos para criar um livro
        $dados = [
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2023'
        ];

        $response = $this->postJson('/livros', $dados);

        $response->assertStatus(201);
        $this->assertDatabaseHas('livro', [
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2023'
        ]);
    }

    /** @test */
    public function deve_retornar_erro_ao_criar_livro_sem_dados_obrigatorios()
    {
        // Envia requisição POST com dados faltando
        $dadosIncompletos = [
            'Titulo' => '',
            'Editora' => '',
        ];

        // Faz uma requisição POST para tentar criar o livro com dados incompletos
        $response = $this->postJson('/livros', $dadosIncompletos);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['Titulo', 'Editora']);
    }

    /** @test */
    public function deve_retornar_lista_de_livros_com_sucesso()
    {
        // Cria alguns registros de exemplo no banco de dados
        $this->livroDAO->criar([
            'Titulo' => 'Livro A',
            'Editora' => 'Editora A',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);

        $this->livroDAO->criar([
            'Titulo' => 'Livro B',
            'Editora' => 'Editora B',
            'Edicao' => 2,
            'AnoPublicacao' => '2021'
        ]);

        $response = $this->getJson('/livros');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'Titulo' => 'Livro A',
            'Editora' => 'Editora A',
            'Edicao' => 1,
            'AnoPublicacao' => '2020',
        ]);
        $response->assertJsonFragment([
            'Titulo' => 'Livro B',
            'Editora' => 'Editora B',
            'Edicao' => 2,
            'AnoPublicacao' => '2021',
        ]);
    }

    /** @test */
    public function deve_retornar_detalhes_do_livro_com_sucesso()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);

        $response = $this->getJson("/livros/{$livro->Codl}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020',
        ]);
    }

    /** @test */
    public function deve_retornar_erro_404_se_livro_nao_existir()
    {
        $response = $this->getJson("/livros/999");
        $response->assertStatus(404);
    }

    /** @test */
    public function deve_atualizar_livro_com_sucesso()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Antigo',
            'Editora' => 'Editora Antiga',
            'Edicao' => 1,
            'AnoPublicacao' => '2010'
        ]);

        $dadosAtualizados = [
            'Codl' => $livro->Codl,
            'Titulo' => 'Livro Atualizado',
            'Editora' => 'Editora Atualizada',
            'Edicao' => 2,
            'AnoPublicacao' => '2021'
        ];

        $response = $this->putJson('/livros', $dadosAtualizados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('livro', [
            'Codl' => $livro->Codl,
            'Titulo' => 'Livro Atualizado',
            'Editora' => 'Editora Atualizada',
            'Edicao' => 2,
            'AnoPublicacao' => '2021',
        ]);
    }

    /** @test */
    public function deve_retornar_erro_404_ao_atualizar_livro_inexistente()
    {
        // Dados de um livro que não existe
        $dadosAtualizados = [
            'Codl' => 999,
            'Titulo' => 'Livro Inexistente',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ];

        $response = $this->putJson('/livros', $dadosAtualizados);

        $response->assertStatus(404);
    }

    /** @test */
    public function deve_deletar_livro_com_sucesso()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);

        $response = $this->deleteJson("/livros/{$livro->Codl}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('livro', ['Codl' => $livro->Codl]);
    }

    /** @test */
    public function deve_retornar_erro_404_ao_deletar_livro_inexistente()
    {
        $response = $this->deleteJson('/livros/999');
        $response->assertStatus(404);
    }

    /** @test */
    public function deve_associar_assunto_a_livro_com_sucesso()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);
        $assunto = $this->assuntoDAO->criar([
            'Descricao' => 'Assunto Teste'
        ]);

        $dadosAssociacao = [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs
        ];

        $response = $this->postJson("/livros/{$livro->Codl}/assuntos", $dadosAssociacao);

        $response->assertStatus(201);
        $this->assertDatabaseHas('livro_assunto', [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs
        ]);
    }

    /** @test */
    public function deve_retornar_erro_500_ao_associar_livro_inexistente()
    {
        // Cria um assunto de exemplo
        $assunto = $this->assuntoDAO->criar([
            'Descricao' => 'Assunto Teste'
        ]);

        $dadosAssociacao = [
            'Livro_codl' => 999, // Livro inexistente
            'Assunto_codAs' => $assunto->codAs
        ];

        $response = $this->postJson("/livros/999/assuntos", $dadosAssociacao);
        $response->assertStatus(500);
    }

    /** @test */
    public function deve_retornar_erro_422_se_assunto_nao_existir()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);

        $dadosAssociacao = [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => 999 // Assunto inexistente
        ];

        $response = $this->postJson("/livros/{$livro->Codl}/assuntos", $dadosAssociacao);
        $response->assertStatus(422);
    }

    /** @test */
    public function deve_desassociar_assunto_de_livro_com_sucesso()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);
        $assunto = $this->assuntoDAO->criar([
            'Descricao' => 'Assunto Teste'
        ]);

        $this->livroAssuntoDAO->criar([
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs
        ]);

        // Verifica que a associação existe no banco de dados
        $this->assertDatabaseHas('livro_assunto', [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs
        ]);

        $response = $this->deleteJson("/livros/{$livro->Codl}/assunto/{$assunto->codAs}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('livro_assunto', [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs
        ]);
    }

    /** @test */
    public function deve_retornar_erro_quando_assunto_nao_estiver_associado_ao_livro()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);
        $assunto = $this->assuntoDAO->criar([
            'Descricao' => 'Assunto Teste'
        ]);

        $response = $this->deleteJson("/livros/{$livro->Codl}/assunto/{$assunto->codAs}");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Associação não encontrada.'
        ]);
    }

    /** @test */
    public function deve_retornar_erro_quando_livro_nao_existe_em_assunto()
    {
        // Cria um assunto de exemplo no banco de dados
        $assunto = $this->assuntoDAO->criar([
            'Descricao' => 'Assunto Teste'
        ]);

        $response = $this->deleteJson("/livros/999/assunto/{$assunto->codAs}");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Associação não encontrada.'
        ]);
    }

    /** @test */
    public function deve_associar_autor_a_livro_com_sucesso()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);
        $autor = $this->autorDAO->criar([
            'Nome' => 'Autor Teste'
        ]);

        $dadosAssociacao = [
            'Autor_CodAu' => $autor->CodAu
        ];

        $response = $this->postJson("/livros/{$livro->Codl}/autores", $dadosAssociacao);

        $response->assertStatus(201);
        $this->assertDatabaseHas('livro_autor', [
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu
        ]);
    }

    /** @test */
    public function deve_retornar_erro_quando_autor_ja_estiver_associado()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);
        $autor = $this->autorDAO->criar([
            'Nome' => 'Autor Teste'
        ]);

        $this->livroAutorDAO->criar([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu
        ]);

        $dadosAssociacao = [
            'Autor_CodAu' => $autor->CodAu
        ];

        $response = $this->postJson("/livros/{$livro->Codl}/autores", $dadosAssociacao);

        $response->assertStatus(201);
    }

    /** @test */
    public function deve_retornar_erro_quando_autor_nao_existe()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);

        $dadosAssociacao = [
            'Autor_CodAu' => 999 // Autor inexistente
        ];

        $response = $this->postJson("/livros/{$livro->Codl}/autores", $dadosAssociacao);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The selected autor  cod au is invalid.'
        ]);
    }

    /** @test */
    public function deve_retornar_erro_quando_livro_nao_existe_em_autor()
    {
        // Cria um autor de exemplo no banco de dados
        $autor = $this->autorDAO->criar([
            'Nome' => 'Autor Teste'
        ]);

        $dadosAssociacao = [
            'Autor_CodAu' => $autor->CodAu
        ];

        $response = $this->postJson("/livros/999/autores", $dadosAssociacao); // Livro inexistente
        $response->assertStatus(500);
    }

    /** @test */
    public function deve_desassociar_autor_a_livro_com_sucesso()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);
        $autor = $this->autorDAO->criar([
            'Nome' => 'Autor Teste'
        ]);

        $this->livroAutorDAO->criar([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu
        ]);

        $response = $this->deleteJson("/livros/{$livro->Codl}/autor/{$autor->CodAu}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('livro_autor', [
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu
        ]);
    }

    /** @test */
    public function deve_retornar_erro_quando_autor_nao_estiver_associado_ao_livro()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);
        $autor = $this->autorDAO->criar([
            'Nome' => 'Autor Teste'
        ]);

        $response = $this->deleteJson("/livros/{$livro->Codl}/autor/{$autor->CodAu}");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Associação não encontrada.'
        ]);
    }

    /** @test */
    public function deve_retornar_erro_quando_autor_nao_existe_em_livro()
    {
        // Cria um livro de exemplo no banco de dados
        $livro = $this->livroDAO->criar([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2020'
        ]);

        $response = $this->deleteJson("/livros/{$livro->Codl}/autor/999"); // Autor inexistente

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Associação não encontrada.'
        ]);
    }


}
