<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o campo 'Titulo' é obrigatório.
     * @test
     */
    public function titulo_e_obrigatorio()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => '', // Campo vazio
            'Editora' => 'Editora X',
            'Edicao' => 1,
            'AnoPublicacao' => '2023'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Titulo')
                 ->assertJson([
                     'errors' => ['Titulo' => ['O campo Título é obrigatório.']]
                 ]);
    }

    /**
     * Testa se o campo 'Titulo' respeita o limite máximo de caracteres.
     * @test
     */
    public function titulo_nao_pode_exceder_40_caracteres()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => str_repeat('a', 41), // Mais de 40 caracteres
            'Editora' => 'Editora X',
            'Edicao' => 1,
            'AnoPublicacao' => '2023'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Titulo')
                 ->assertJson([
                     'errors' => ['Titulo' => ['O campo Título não pode exceder 40 caracteres.']]
                 ]);
    }

    /**
     * Testa se o campo 'Editora' é obrigatório.
     * @test
     */
    public function editora_e_obrigatoria()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => 'Livro X',
            'Editora' => '', // Campo vazio
            'Edicao' => 1,
            'AnoPublicacao' => '2023'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Editora')
                 ->assertJson([
                     'errors' => ['Editora' => ['O campo Editora é obrigatório.']]
                 ]);
    }

    /**
     * Testa se o campo 'Editora' respeita o limite máximo de caracteres.
     * @test
     */
    public function editora_nao_pode_exceder_40_caracteres()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => 'Livro X',
            'Editora' => str_repeat('a', 41), // Mais de 40 caracteres
            'Edicao' => 1,
            'AnoPublicacao' => '2023'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Editora')
                 ->assertJson([
                     'errors' => ['Editora' => ['O campo Editora não pode exceder 40 caracteres.']]
                 ]);
    }

    /**
     * Testa se o campo 'Edicao' deve ser um número inteiro.
     * @test
     */
    public function edicao_deve_ser_inteiro()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => 'Livro X',
            'Editora' => 'Editora X',
            'Edicao' => 'um', // Valor não inteiro
            'AnoPublicacao' => '2023'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Edicao')
                 ->assertJson([
                     'errors' => ['Edicao' => ['O campo Edição deve ser um número inteiro.']]
                 ]);
    }

    /**
     * Testa se o campo 'Edicao' deve ser maior ou igual a 1.
     * @test
     */
    public function edicao_deve_ser_maior_ou_igual_a_1()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => 'Livro X',
            'Editora' => 'Editora X',
            'Edicao' => 0, // Valor menor que 1
            'AnoPublicacao' => '2023'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Edicao')
                 ->assertJson([
                     'errors' => ['Edicao' => ['O campo Edição deve ser maior ou igual a 1.']]
                 ]);
    }

    /**
     * Testa se o campo 'AnoPublicacao' deve ter exatamente 4 dígitos.
     * @test
     */
    public function ano_publicacao_deve_ter_4_digitos()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => 'Livro X',
            'Editora' => 'Editora X',
            'Edicao' => 1,
            'AnoPublicacao' => '23' // Menos de 4 dígitos
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('AnoPublicacao')
                 ->assertJson([
                     'errors' => ['AnoPublicacao' => ['O campo Ano de Publicação deve ter 4 dígitos.']]
                 ]);
    }

    /**
     * Testa se o campo 'AnoPublicacao' deve ser um número válido de 4 dígitos.
     * @test
     */
    public function ano_publicacao_deve_ser_numero_valido()
    {
        $response = $this->postJson('/livros', [
            'Titulo' => 'Livro X',
            'Editora' => 'Editora X',
            'Edicao' => 1,
            'AnoPublicacao' => 'abcd' // Não é um número
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('AnoPublicacao')
                 ->assertJson([
                     'errors' => ['AnoPublicacao' => ['O campo Ano de Publicação deve ser um ano válido.']]
                 ]);
    }
}