<?php

namespace Tests\Feature;

use App\DAO\AssuntoDAO;
use App\Models\Assunto;
use App\Models\Livro;
use App\Models\LivroAssunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssuntoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function lista_todos_os_assuntos_com_sucesso()
    {
        // Cria manualmente 3 Assuntos no banco de dados
        Assunto::create(['Descricao' => 'Assunto 1']);
        Assunto::create(['Descricao' => 'Assunto 2']);
        Assunto::create(['Descricao' => 'Assunto 3']);

        $response = $this->getJson('/assuntos');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json());
    }

    /** @test */
    public function lista_assuntos_retorna_vazio_quando_nao_ha_assuntos()
    {
        // Faz a requisição para o método index sem criar nenhum assunto
        $response = $this->getJson('/assuntos');

        $response->assertStatus(200);
        $this->assertEmpty($response->json());
    }

    /** @test */
    public function cria_um_novo_assunto_com_sucesso()
    {
        $response = $this->postJson('/assuntos', [
            'Descricao' => 'Assunto Teste',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['Descricao' => 'Assunto Teste']);
        $this->assertDatabaseHas('assunto', ['Descricao' => 'Assunto Teste']);
    }

    /** @test */
    public function nao_cria_assunto_quando_a_descricao_estiver_vazia()
    {
        // Dados inválidos para criar um novo assunto (Descrição vazia)
        $data = [
            'Descricao' => '',
        ];

        $response = $this->postJson('/assuntos', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['Descricao']);
    }

    /** @test */
    public function nao_cria_assunto_quando_a_descricao_exceder_o_maximo_de_caracteres()
    {
        // Dados inválidos para criar um novo assunto (Descrição muito longa)
        $data = [
            'Descricao' => str_repeat('A', 41), // 41 caracteres
        ];

        $response = $this->postJson('/assuntos', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['Descricao']);
    }

    /** @test */
    public function mostra_assunto_com_sucesso()
    {
        // Criar um assunto no banco de dados
        $assunto = Assunto::create(['Descricao' => 'Assunto Exemplo']);

        $response = $this->getJson("/assuntos/{$assunto->codAs}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['Descricao' => 'Assunto Exemplo']);
    }

    /** @test */
    public function nao_mostra_assunto_quando_o_id_nao_existir()
    {
        // Faz a requisição para o método show com um ID que não existe
        $response = $this->getJson('/assuntos/9999'); // ID que não existe

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Assunto não encontrado']);
    }

    /** @test */
    public function atualiza_assunto_com_sucesso()
    {
        // Criar um assunto no banco de dados
        $assunto = Assunto::create([
            'Descricao' => 'Assunto Antigo',
        ]);

        $dadosAtualizados = [
            'codAs' => $assunto->codAs,
            'Descricao' => 'Assunto Atualizado'
        ];

        $response = $this->putJson("/assuntos", $dadosAtualizados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('assunto', $dadosAtualizados);
    }

    /** @test */
    public function nao_atualiza_assunto_quando_o_id_nao_existir()
    {
        // Dados atualizados
        $dadosAtualizados = [
            'codAs' => '9999', // ID que não existe
            'Descricao' => 'Assunto Atualizado'
        ];

        $response = $this->putJson('/assuntos', $dadosAtualizados);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Assunto não encontrado']);
    }

    // /** @test */
    public function nao_atualiza_assunto_quando_os_dados_sao_invalidos()
    {
        // Criar um assunto no banco de dados
        $assunto = Assunto::create([
            'Descricao' => 'Assunto Antigo'
        ]);

        $dadosInvalidos = [
            'codAs' => $assunto->codAs,
            'Descricao' => str_repeat('A', 41)
        ];

        $response = $this->putJson("/assuntos", $dadosInvalidos);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['Descricao']);
    }

    /** @test */
    public function exclui_assunto_existente_com_sucesso()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Assunto Teste',
        ]);

        $response = $this->deleteJson("/assuntos/{$assunto->codAs}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('assunto', ['codAs' => $assunto->codAs]);
    }

    /** @test */
    public function tenta_excluir_assunto_nao_existente()
    {
        $response = $this->deleteJson('/assuntos/9999');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Assunto não encontrado']);
    }

    /** @test */
    public function erro_ao_excluir_assunto()
    {
        // Simular uma exceção ao tentar excluir o assunto
        $this->mock(AssuntoDAO::class, function ($mock) {
            $mock->shouldReceive('deletar')->andThrow(new \Exception('Erro inesperado'));
        });

        $assunto = Assunto::create([
            'Descricao' => 'Assunto Teste',
        ]);

        $response = $this->deleteJson("/assuntos/{$assunto->codAs}");
        $response->assertStatus(500);
    }

    /** @test */
    public function associa_livro_a_assunto_com_sucesso()
    {
        // Criar um assunto e um livro no banco de dados
        $assunto = Assunto::create(['Descricao' => 'Assunto Teste']);
        $livro = Livro::create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $dadosAssociacao = [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ];

        $response = $this->postJson("/assuntos/{$assunto->codAs}/livros", $dadosAssociacao);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Livro associado ao Assunto com sucesso.']);
        $this->assertDatabaseHas('livro_assunto', $dadosAssociacao);
    }

    /** @test */
    public function tenta_associar_assunto_inexistente()
    {
        // Criar um livro no banco de dados
        $livro = Livro::create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        // Dados para a associação
        $dadosAssociacao = [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => 9999, // ID de assunto que não existe
        ];

        $response = $this->postJson('/assuntos/9999/livros', $dadosAssociacao);

        $response->assertStatus(500);
    }

    /** @test */
    public function tenta_associar_livro_inexistente()
    {
        // Criar um assunto no banco de dados
        $assunto = Assunto::create([
            'Descricao' => 'Assunto Teste'
        ]);

        $dadosAssociacao = [
            'Livro_codl' => 9999, // ID de livro que não existe
            'Assunto_codAs' => $assunto->codAs,
        ];

        $response = $this->postJson("/assuntos/{$assunto->codAs}/livros", $dadosAssociacao);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('Livro_codl');
    }

    /** @test */
    public function desassocia_livro_de_assunto_com_sucesso()
    {
        // Criar um assunto e um livro
        $assunto = Assunto::create(['Descricao' => 'Assunto Teste']);
        $livro = Livro::create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        LivroAssunto::create([
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ]);

        $response = $this->deleteJson("/assuntos/{$assunto->codAs}/livros/{$livro->Codl}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Livro desassociado do Assunto com sucesso.']);
        $this->assertDatabaseMissing('livro_assunto', [
            'Livro_codl' => $livro->Codl,
            'Assunto_codAs' => $assunto->codAs,
        ]);
    }

    /** @test */
    public function nao_desassocia_livro_de_assunto_quando_a_associacao_nao_existir()
    {
        // Criar um assunto e um livro
        $assunto = Assunto::create(['Descricao' => 'Assunto Teste']);
        $livro = Livro::create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $response = $this->deleteJson("/assuntos/{$assunto->codAs}/livros/{$livro->Codl}");

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Associação não encontrada.']);
    }

    /** @test */
    public function nao_desassocia_livro_quando_o_assunto_inexistente()
    {
        // Criar um livro
        $livro = Livro::create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $response = $this->deleteJson("/assuntos/9999/livros/{$livro->Codl}");

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Associação não encontrada.']);
    }

    /** @test */
    public function nao_desassocia_livro_quando_o_livro_inexistente()
    {
        // Criar um assunto
        $assunto = Assunto::create(['Descricao' => 'Assunto Teste']);

        $response = $this->deleteJson("/assuntos/{$assunto->codAs}/livros/9999");

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Associação não encontrada.']);
    }
}
