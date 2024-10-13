<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_um_livro()
    {
        $data = [
            'Titulo' => 'O Senhor dos Anéis',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '2001',
        ];

        $livro = Livro::create($data);

        $this->assertDatabaseHas('livro', $data);
        $this->assertEquals('O Senhor dos Anéis', $livro->Titulo);
    }

    /** @test */
    public function pode_listar_livros()
    {
        Livro::create([
            'Titulo' => '1984',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1949',
        ]);

        $livros = Livro::all();

        $this->assertCount(1, $livros);
        $this->assertEquals('1984', $livros[0]->Titulo);
    }

    /** @test */
    public function pode_buscar_um_livro()
    {
        $livro = Livro::create([
            'Titulo' => 'Fahrenheit 451',
            'Editora' => 'Ray Bradbury',
            'Edicao' => 1,
            'AnoPublicacao' => '1953',
        ]);

        $foundLivro = Livro::find($livro->Codl);

        $this->assertNotNull($foundLivro);
        $this->assertEquals('Fahrenheit 451', $foundLivro->Titulo);
    }

    /** @test */
    public function titulo_e_obrigatorio()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Livro::create([
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
        ]);
    }

    /** @test */
    public function editora_e_obrigatoria()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Livro::create([
            'Titulo' => 'Título Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
        ]);
    }

    /** @test */
    public function pode_criar_livro_com_campos_obrigatorios()
    {
        $livro = Livro::create([
            'Titulo' => 'Título Exemplo',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
        ]);

        $this->assertDatabaseHas('livro', [
            'Titulo' => 'Título Exemplo',
            'Editora' => 'Editora Exemplo',
        ]);
        $this->assertNotNull($livro->Codl); // Verifica se o ID foi gerado
    }

    /** @test */
    public function pode_atualizar_um_livro()
    {
        $livro = Livro::create([
            'Titulo' => 'O Hobbit',
            'Editora' => 'HarperCollins',
            'Edicao' => 1,
            'AnoPublicacao' => '1937',
        ]);

        $livro->update([
            'Titulo' => 'O Hobbit: Edição Especial',
            'Edicao' => 2,
        ]);

        $this->assertDatabaseHas('livro', [
            'Codl' => $livro->Codl,
            'Titulo' => 'O Hobbit: Edição Especial',
            'Edicao' => 2,
        ]);
    }

    /** @test */
    public function pode_excluir_um_livro()
    {
        $livro = Livro::create([
            'Titulo' => 'Brave New World',
            'Editora' => 'Aldous Huxley',
            'Edicao' => 1,
            'AnoPublicacao' => '1932',
        ]);
    
        $livroId = $livro->Codl; // Armazenar o ID para verificar depois
    
        $livro->delete();
    
        // Verifica se o livro foi deletado
        $this->assertDatabaseMissing('livro', ['Codl' => $livroId]);
    }
}
