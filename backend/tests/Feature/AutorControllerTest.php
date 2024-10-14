<?php

namespace Tests\Feature;

use App\DAO\AutorDAO;
use App\DAO\LivroAutorDAO;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function lista_todos_autores()
    {
        // Criar alguns autores de exemplo
        Autor::create(['Nome' => 'Autor 1']);
        Autor::create(['Nome' => 'Autor 2']);
        Autor::create(['Nome' => 'Autor 3']);

        // Fazer a requisição GET para a rota /autores
        $response = $this->getJson('/autores');
// dd($response);
        // Verificar se o status da resposta é 200
        $response->assertStatus(200);

        // Verificar se os dados dos autores estão presentes na resposta
        // $response->assertJsonCount(3, 'data'); // Espera 3 autores na resposta
        $response->assertJsonFragment(['Nome' => 'Autor 1']);
        $response->assertJsonFragment(['Nome' => 'Autor 2']);
        $response->assertJsonFragment(['Nome' => 'Autor 3']);
    }

    /** @test */
    public function retorna_erro_500_na_excessao_da_query()
    {
        // Simular uma falha na consulta ao banco de dados
        $this->mock(\App\DAO\AutorDAO::class, function ($mock) {
            $mock->shouldReceive('listar')
                ->andThrow(new \Exception('Erro genérico ao buscar autores'));
        });

        // Fazer a requisição GET para a rota /autores
        $response = $this->getJson('/autores');

        // Verificar se o status da resposta é 500
        $response->assertStatus(500)
                ->assertJsonStructure([
                    'message',
                    'exception',
                    'file',
                    'line',
                    'trace',
                ]);
    }

    /** @test */
    public function pode_criar_um_autor_com_sucesso()
    {
        // Dados válidos para criar um novo autor
        $data = [
            'Nome' => 'Autor Teste',
        ];

        // Fazer a requisição POST para a rota /autores
        $response = $this->postJson('/autores', $data);

        // Verificar se o status da resposta é 201 (Created)
        $response->assertStatus(201);

        // Verificar se o autor foi realmente criado no banco de dados
        $this->assertDatabaseHas('autor', $data);
    }

    /** @test */
    public function valida_campos_obrigatorios()
    {
        // Dados inválidos (campos obrigatórios ausentes)
        $data = [
            // 'nome' => 'Autor Teste', // Comentado para simular erro
            'email' => 'autor@teste.com', //campo inventado para não enviar array vazio
        ];

        // Fazer a requisição POST para a rota /autores
        $response = $this->postJson('/autores', $data);

        // Verificar se o status da resposta é 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Verificar se a resposta contém a mensagem de erro esperada
        $response->assertJsonValidationErrors(['Nome']);
    }

    /** @test */
    public function lista_um_autor_com_sucesso()
    {
        // Criar um autor para testar
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        // Fazer a requisição GET para a rota /autores/{id}
        $response = $this->getJson("/autores/{$autor->CodAu}");

        // Verificar se o status da resposta é 200 (OK)
        $response->assertStatus(200);

        // Verificar se a resposta contém os dados do autor
        $response->assertJson([
            'CodAu' => $autor->CodAu,
            'Nome' => 'Autor Teste',
        ]);
    }

    /** @test */
    public function retorna_erro_autor_nao_encontrado()
    {
        // Tentar obter um autor que não existe
        $response = $this->getJson("/autores/9999"); // ID que não existe

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Autor não encontrado']);
    }

    /** @test */
    public function atualiza_autor_existente_com_sucesso()
    {
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $dadosAtualizados = [
            'CodAu' => $autor->CodAu,
            'Nome' => 'Autor Teste Atualizado',
        ];

        $response = $this->putJson("/autores", $dadosAtualizados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('autor', $dadosAtualizados);
    }

    /** @test */
    public function tentar_atualizar_autor_nao_existente()
    {
        // Dados para atualização
        $dadosAtualizados = [
            'CodAu' => '9999', //ID inexistente
            'Nome' => 'Autor Inexistente',
        ];

        $response = $this->putJson("/autores", $dadosAtualizados);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Autor não encontrado']);
    }

    /** @test */
    public function tentar_atualizar_autor_com_dados_invalidos()
    {
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $dadosInvalidos = [
            'CodAu' => $autor->CodAu,
            'Nome' => '',
        ];

        $response = $this->putJson("/autores", $dadosInvalidos);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['Nome']);
    }

    /** @test */
    public function exclui_autor_existente_com_sucesso()
    {
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $response = $this->deleteJson("/autores/{$autor->CodAu}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('autor', ['CodAu' => $autor->CodAu]);
    }

    /** @test */
    public function tenta_excluir_autor_nao_existente()
    {
        $response = $this->deleteJson('/autores/9999');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Autor não encontrado']);
    }

    /** @test */
    public function erro_ao_excluir_autor()
    {
        // Simular uma exceção ao tentar excluir o autor
        $this->mock(AutorDAO::class, function ($mock) {
            $mock->shouldReceive('deletar')->andThrow(new \Exception('Erro inesperado'));
        });

        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $response = $this->deleteJson("/autores/{$autor->CodAu}");
        $response->assertStatus(500)
                ->assertJsonStructure([
                    'message',
                    'exception',
                    'file',
                    'line',
                    'trace',
                ]);
    }

    /** @test */
    public function associa_livro_a_autor_com_sucesso()
    {
        // Criar um autor e um livro
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $livro = Livro::create([
            'Codl' => 123,
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $dados = [
            'livro_codl' => $livro->Codl,
        ];

        $response = $this->postJson("/autores/{$autor->CodAu}/livros", $dados);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Livro associado ao autor com sucesso.']);
        $this->assertDatabaseHas('livro_autor', [
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);
    }

    /** @test */
    public function tenta_associar_livro_inexistente()
    {
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $dados = [
            'livro_codl' => 9999, // Código de livro que não existe
        ];

        $response = $this->postJson("/autores/{$autor->CodAu}/livros", $dados);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('livro_codl');
    }

    /** @test */
    public function tenta_associar_livro_sem_informar_livro_codl()
    {
        // Criar um autor
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $response = $this->postJson("/autores/{$autor->CodAu}/livros", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('livro_codl');
    }

    /** @test */
    public function erro_ao_associar_livro_ao_autor()
    {
        // Simular uma exceção ao tentar criar a associação
        $this->mock(LivroAutorDAO::class, function ($mock) {
            $mock->shouldReceive('criar')->andThrow(new \Exception('Erro inesperado'));
        });

        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $livro = Livro::create([
            'Codl' => 123,
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $dados = [
            'livro_codl' => $livro->Codl,
        ];

        $response = $this->postJson("/autores/{$autor->CodAu}/livros", $dados);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Erro ao associar livro ao autor: Erro inesperado']);
    }

    /** @test */
    public function desassocia_livro_de_autor_com_sucesso()
    {
        // Criar um autor e um livro
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $livro = Livro::create([
            'Codl' => 123,
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $LivroAutorDAO = new LivroAutorDAO();
        $LivroAutorDAO->criar([
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);

        $response = $this->deleteJson("/autores/{$autor->CodAu}/livros/{$livro->Codl}");

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Livro desassociado do autor com sucesso.']);
        $this->assertDatabaseMissing('livro_autor', [
            'Livro_Codl' => $livro->Codl,
            'Autor_CodAu' => $autor->CodAu,
        ]);
    }

    /** @test */
    public function tenta_desassociar_livro_nao_associado()
    {
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $livro = Livro::create([
            'Codl' => 123,
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $response = $this->deleteJson("/autores/{$autor->CodAu}/livros/{$livro->Codl}");

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Associação não encontrada.']);
    }

    /** @test */
    public function erro_ao_desassociar_livro_do_autor()
    {
        // Simular uma exceção ao tentar deletar a associação
        $this->mock(LivroAutorDAO::class, function ($mock) {
            $mock->shouldReceive('deletar')->andThrow(new \Exception('Erro inesperado'));
        });

        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        $livro = Livro::create([
            'Codl' => 123,
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora de teste',
            'Edicao' => 1,
            'AnoPublicacao' => '1954',
        ]);

        $response = $this->deleteJson("/autores/{$autor->CodAu}/livros/{$livro->Codl}");

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Erro ao desassociar livro do autor: Erro inesperado']);
    }

    /** @test */
    public function busca_autor_por_nome_com_sucesso()
    {
        // Criar um autor
        $autor = Autor::create([
            'Nome' => 'Autor Teste',
        ]);

        // Fazer a requisição para buscar o autor pelo nome
        $response = $this->getJson("/autores/buscar/nome/Autor%20Teste");

        $response->assertStatus(200);
        $response->assertJsonFragment(['Nome' => 'Autor Teste']);
    }

    /** @test */
    public function busca_autor_por_nome_nao_existente()
    {
        // Fazer a requisição para buscar um autor que não existe
        $response = $this->getJson("/autores/buscar/nome/NomeInexistente");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

}
