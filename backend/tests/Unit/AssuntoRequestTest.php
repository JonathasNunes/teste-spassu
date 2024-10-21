<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssuntoRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o campo 'Descricao' é obrigatório.
     * @test
     */
    public function descricao_e_obrigatorio()
    {
        $response = $this->postJson('/api/assuntos', [
            'Descricao' => '', // Campo vazio
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Descricao')
                 ->assertJson([
                     'errors' => ['Descricao' => ['O campo Descrição é obrigatório.']]
                 ]);
    }

    /**
     * Testa se o campo 'Descricao' respeita o limite máximo de caracteres.
     * @test
     */
    public function descricao_nao_pode_exceder_40_caracteres()
    {
        $response = $this->postJson('/api/assuntos', [
            'Descricao' => str_repeat('a', 41), // Mais de 40 caracteres
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Descricao')
                 ->assertJson([
                     'errors' => ['Descricao' => ['O campo Descrição não pode exceder 40 caracteres.']]
                 ]);
    }
}