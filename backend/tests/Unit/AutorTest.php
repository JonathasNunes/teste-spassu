<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AutorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_um_autor()
    {
        $autor = Autor::create([
            'Nome' => 'John Doe',
        ]);

        $this->assertDatabaseHas('autor', [
            'Nome' => 'John Doe',
        ]);
    }

    /** @test */
    public function pode_listar_autores()
    {
        Autor::create([
            'Nome' => 'John Wick',
        ]);

        $autores = Autor::all();

        $this->assertCount(1, $autores);
        $this->assertEquals('John Wick', $autores[0]->Nome);
    }

    /** @test */
    public function pode_buscar_um_autor()
    {
        $author = Autor::create([
            'Nome' => 'John Doe',
        ]);

        $foundAutor = Autor::where('Nome', $author->Nome)->first();

        $this->assertNotNull($foundAutor);
        $this->assertEquals('John Doe', $foundAutor->Nome);
    }

    /** @test */
    public function um_autor_requer_um_nome()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Autor::create([
            'Nome' => null, // Testando a validação de que o nome não pode ser nulo
        ]);
    }

    /** @test */
    public function pode_atualizar_um_autor()
    {
        $autor = Autor::create([
            'Nome' => 'John Doe',
        ]);

        // Atualizando o autor
        $autor->update(['Nome' => 'Jane Doe']);

        $this->assertDatabaseHas('autor', [
            'Nome' => 'Jane Doe',
        ]);
        $this->assertDatabaseMissing('autor', [
            'Nome' => 'John Doe',
        ]);
    }

    /** @test */
    public function pode_excluir_um_autor()
    {
        $autor = Autor::create([
            'Nome' => 'John Doe',
        ]);

        // Excluindo o autor
        $autor->delete();

        $this->assertDatabaseMissing('autor', [
            'Nome' => 'John Doe',
        ]);
    }

}
