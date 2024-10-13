<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssuntoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_um_assunto()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Tecnologia',
        ]);

        $this->assertDatabaseHas('assunto', [
            'Descricao' => 'Tecnologia'
        ]);
    }

    /** @test */
    public function pode_listar_assuntos()
    {
        Assunto::create([
            'Descricao' => 'Antiguidades',
        ]);

        $assuntos = Assunto::all();

        $this->assertCount(1, $assuntos);
        $this->assertEquals('Antiguidades', $assuntos[0]->Descricao);
    }

    /** @test */
    public function pode_buscar_um_assunto()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Ficção',
        ]);

        $foundAssunto = Assunto::where('Descricao', $assunto->Descricao)->first();

        $this->assertNotNull($foundAssunto);
        $this->assertEquals('Ficção', $foundAssunto->Descricao);
    }

    /** @test */
    public function um_assunto_requer_uma_descricao()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Assunto::create([
            'Descricao' => null, // Testando a validação de que a descrição não pode ser nula
        ]);
    }

    /** @test */
    public function pode_atualizar_um_assunto()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Ciência',
        ]);

        // Atualizando o assunto
        $assunto->update(['Descricao' => 'História']);

        $this->assertDatabaseHas('assunto', [
            'Descricao' => 'História',
        ]);
        $this->assertDatabaseMissing('assunto', [
            'Descricao' => 'Ciência',
        ]);
    }

    /** @test */
    public function pode_excluir_um_assunto()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Educação',
        ]);

        // Excluindo o autor
        $assunto->delete();

        $this->assertDatabaseMissing('assunto', [
            'Descricao' => 'Educação',
        ]);
    }
}
