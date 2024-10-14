<?php

namespace App\Http\Controllers;

use App\DAO\AssuntoDAO;
use App\DAO\LivroAssuntoDAO;
use App\Http\Requests\AssuntoRequest;
use Illuminate\Http\Request;

class AssuntoController extends Controller
{
    protected $assuntoDAO;
    protected $livroAssuntoDAO;

    public function __construct(AssuntoDAO $assuntoDAO, LivroAssuntoDAO $livroAssuntoDAO)
    {
        $this->assuntoDAO = $assuntoDAO;
        $this->livroAssuntoDAO = $livroAssuntoDAO;
    }

    // Método para criar um novo Assunto
    public function store(AssuntoRequest $request)
    {
        try {
            $assunto = $this->assuntoDAO->criar($request->validated());
            return response()->json($assunto, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar Assunto: ' . $e->getMessage()], 500);
        }
    }

    // Método para listar todos os Assuntos
    public function index()
    {
        return response()->json($this->assuntoDAO->listar());
    }

    // Método para buscar um Assunto pelo ID
    public function show($id)
    {
        $assunto = $this->assuntoDAO->buscarPorId($id);
        if ($assunto) {
            return response()->json($assunto);
        }
        return response()->json(['error' => 'Assunto não encontrado'], 404);
    }

    // Método para atualizar um Assunto existente
    public function update(AssuntoRequest $request)
    {
        try {
            $atualizado = $this->assuntoDAO->atualizar($request['codAs'], $request->validated());
            if (!$atualizado) {
                return response()->json(['error' => 'Assunto não encontrado'], 404);
            }
            return response()->json($atualizado, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar Assunto: ' . $e->getMessage()], 500);
        }
    }

    // Método para deletar um Assunto
    public function destroy($id)
    {
        try {
            $deletado = $this->assuntoDAO->deletar($id);
            if ($deletado) {
                return response()->json(['message' => 'Assunto deletado com sucesso']);
            }
            return response()->json(['error' => 'Assunto não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar Assunto: ' . $e->getMessage()], 500);
        }
    }

    // Método para associar um Livro a um Assunto
    public function associarLivro(Request $request, $assuntoId)
    {
        $request->validate([
            'Livro_codl' => 'required|integer|exists:livro,Codl',
        ]);

        try {
            $this->livroAssuntoDAO->criar([
                'Livro_codl' => $request->Livro_codl,
                'Assunto_codAs' => $assuntoId,
            ]);
            return response()->json(['message' => 'Livro associado ao Assunto com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao associar livro ao Assunto: ' . $e->getMessage()], 500);
        }
    }

    // Método para desassociar um Livro de um Assunto
    public function desassociarLivro($assuntoId, $livroCodl)
    {
        try {
            $deleted = $this->livroAssuntoDAO->deletar($livroCodl, $assuntoId);
            if ($deleted) {
                return response()->json(['message' => 'Livro desassociado do Assunto com sucesso.']);
            } else {
                return response()->json(['message' => 'Associação não encontrada.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao desassociar livro do Assunto: ' . $e->getMessage()], 500);
        }
    }
}
