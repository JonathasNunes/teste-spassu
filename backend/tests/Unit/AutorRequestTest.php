<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o campo 'Nome' é obrigatório.
     * @test
     */
    public function nome_e_obrigatorio()
    {
        $response = $this->postJson('/autores', [
            'Nome' => '', // Campo vazio
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Nome')
                 ->assertJson([
                     'errors' => ['Nome' => ['O campo Nome é obrigatório.']]
                 ]);
    }

    /**
     * Testa se o campo 'Nome' respeita o limite máximo de caracteres.
     * @test
     */
    public function nome_nao_pode_exceder_40_caracteres()
    {
        $response = $this->postJson('/autores', [
            'Nome' => str_repeat('a', 41), // Mais de 40 caracteres
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('Nome')
                 ->assertJson([
                     'errors' => ['Nome' => ['O campo Nome não pode exceder 40 caracteres.']]
                 ]);
    }
}