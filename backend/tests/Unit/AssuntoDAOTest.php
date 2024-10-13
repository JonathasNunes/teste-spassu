<?php

namespace Tests\Unit;

use App\DAO\AssuntoDAO;
use App\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssuntoDAOTest extends TestCase
{
    use RefreshDatabase;

    protected $assuntoDAO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assuntoDAO = new AssuntoDAO();
    }

    /** @test */
    public function pode_criar_assunto()
    {
        $data = [
            'Descricao' => 'Fantasia',
        ];

        $assunto = $this->assuntoDAO->criar($data);

        $this->assertInstanceOf(Assunto::class, $assunto);
        $this->assertEquals($data['Descricao'], $assunto->Descricao);
    }

    /** @test */
    public function pode_buscar_assunto_por_id()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        $assuntoBuscado = $this->assuntoDAO->buscarPorId($assunto->codAs);

        $this->assertEquals($assunto->codAs, $assuntoBuscado->codAs);
    }

    /** @test */
    public function pode_atualizar_assunto()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        $data = [
            'Descricao' => 'Aventura',
        ];

        $atualizado = $this->assuntoDAO->atualizar($assunto->codAs, $data);
        $assuntoAtualizado = $this->assuntoDAO->buscarPorId($assunto->codAs);

        $this->assertTrue($atualizado);
        $this->assertEquals($data['Descricao'], $assuntoAtualizado->Descricao);
    }

    /** @test */
    public function pode_deletar_assunto()
    {
        $assunto = Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        $deletado = $this->assuntoDAO->deletar($assunto->codAs);
        $assuntoBuscado = $this->assuntoDAO->buscarPorId($assunto->codAs);

        $this->assertTrue($deletado);
        $this->assertNull($assuntoBuscado);
    }

    /** @test */
    public function pode_listar_assuntos()
    {
        Assunto::create([
            'Descricao' => 'Fantasia',
        ]);

        Assunto::create([
            'Descricao' => 'Aventura',
        ]);

        $assuntos = $this->assuntoDAO->listar();

        $this->assertCount(2, $assuntos);
    }

    /** @test */
    public function nao_pode_criar_assunto_sem_descricao()
    {
        $data = []; // Nenhum campo

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->assuntoDAO->criar($data);
    }
}
