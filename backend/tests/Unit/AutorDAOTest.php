<?php

namespace Tests\Unit;

use App\DAO\AutorDAO;
use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorDAOTest extends TestCase
{
    use RefreshDatabase;

    protected $autorDAO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->autorDAO = new AutorDAO();
    }

    /** @test */
    public function pode_criar_autor()
    {
        $data = [
            'Nome' => 'J.R.R. Tolkien',
        ];

        $autor = $this->autorDAO->criar($data);

        $this->assertInstanceOf(Autor::class, $autor);
        $this->assertEquals($data['Nome'], $autor->Nome);
    }

    /** @test */
    public function pode_buscar_autor_por_id()
    {
        $autor = Autor::create([
            'Nome' => 'J.R.R. Tolkien',
        ]);

        $autorBuscado = $this->autorDAO->buscarPorId($autor->CodAu);

        $this->assertEquals($autor->CodAu, $autorBuscado->CodAu);
    }

    /** @test */
    public function pode_atualizar_autor()
    {
        $autor = Autor::create([
            'Nome' => 'J.R.R. Tolkien',
        ]);

        $data = [
            'Nome' => 'Tolkien',
        ];

        $atualizado = $this->autorDAO->atualizar($autor->CodAu, $data);
        $autorAtualizado = $this->autorDAO->buscarPorId($autor->CodAu);

        $this->assertTrue($atualizado);
        $this->assertEquals($data['Nome'], $autorAtualizado->Nome);
    }

    /** @test */
    public function pode_deletar_autor()
    {
        $autor = Autor::create([
            'Nome' => 'J.R.R. Tolkien',
        ]);

        $deletado = $this->autorDAO->deletar($autor->CodAu);
        $autorBuscado = $this->autorDAO->buscarPorId($autor->CodAu);

        $this->assertTrue($deletado);
        $this->assertNull($autorBuscado);
    }

    /** @test */
    public function pode_listar_autores()
    {
        Autor::create([
            'Nome' => 'J.R.R. Tolkien',
        ]);

        Autor::create([
            'Nome' => 'George R.R. Martin',
        ]);

        $autores = $this->autorDAO->listar();

        $this->assertCount(2, $autores);
    }

    /** @test */
    public function nao_pode_criar_autor_sem_nome()
    {
        $data = []; // Nenhum campo

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->autorDAO->criar($data);
    }
}
