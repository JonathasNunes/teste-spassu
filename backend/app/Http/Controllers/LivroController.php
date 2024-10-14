<?php

namespace App\Http\Controllers;

use App\DAO\LivroAssuntoDAO;
use App\DAO\LivroAutorDAO;
use App\DAO\LivroDAO;
use App\Http\Requests\LivroRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    protected $livroDAO;
    protected $livroAutorDAO;
    protected $livroAssuntoDAO;

    public function __construct()
    {
        $this->livroDAO = new LivroDAO();
        $this->livroAutorDAO = new LivroAutorDAO();
        $this->livroAssuntoDAO = new LivroAssuntoDAO();
    }

    // Listar todos os livros
    public function index()
    {
        return response()->json($this->livroDAO->listar());
    }

    // Criar um novo livro
    public function store(LivroRequest $request)
    {
        try{
            $livro = $this->livroDAO->criar($request->validated());
            return response()->json($livro, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar Livro: ' . $e->getMessage()], 500);
        }
    }

    // Mostrar detalhes de um livro específico
    public function show(int $id)
    {
        $livro = $this->livroDAO->buscarPorId($id);
        if (!$livro) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        }
        return response()->json($livro);
    }

    // Atualizar um livro existente
    public function update(LivroRequest $request)
    {
        try{
            $atualizado = $this->livroDAO->atualizar($request['Codl'], $request->validated());
            if (!$atualizado) {
                return response()->json(['message' => 'Livro não encontrado ou não atualizado.'], 404);
            }
            return response()->json($atualizado, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar Livro: ' . $e->getMessage()], 500);
        }
    }

    // Deletar um livro
    public function destroy(int $id)
    {
        try{
            $deletado = $this->livroDAO->deletar($id);
            if (!$deletado) {
                return response()->json(['message' => 'Livro não encontrado.'], 404);
            }
            return response()->json(['message' => 'Livro deletado com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar Livro: ' . $e->getMessage()], 500);
        }
    }

    // Associar livro a um assunto
    public function associarAssunto(Request $request, $id)
    {
        $request->validate([
            'Assunto_codAs' => 'required|integer|exists:assunto,codAs' // Validação do código do assunto
        ]);

        try{
            $data = [
                'Livro_codl' => $id,
                'Assunto_codAs' => $request->input('Assunto_codAs'),
            ];

            $livroAssunto = $this->livroAssuntoDAO->criar($data);
            return response()->json($livroAssunto, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao associar livro ao Assunto: ' . $e->getMessage()], 500);
        }
    }

    // Desassociar livro de um assunto
    public function desassociarAssunto($id, $assuntoId)
    {
        try{
            $deletado = $this->livroAssuntoDAO->deletar($id, $assuntoId);

            if (!$deletado) {
                return response()->json(['message' => 'Associação não encontrada.'], 404);
            }

            return response()->json(['message' => 'Associação desfeita com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao desassociar livro do Assunto: ' . $e->getMessage()], 500);
        }
    }

    // Associar livro a um autor
    public function associarAutor(Request $request, int $id)
    {
        $request->validate([
            'Autor_CodAu' => 'required|integer|exists:autor,CodAu' // Validação do código do autor
        ]);

        try{
            $data = [
                'Livro_Codl' => $id,
                'Autor_CodAu' => $request->input('Autor_CodAu'),
            ];

            $livroAutor = $this->livroAutorDAO->criar($data);

            return response()->json($livroAutor, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao associar livro ao Autor: ' . $e->getMessage()], 500);
        }
    }

    // Desassociar livro de um autor
    public function desassociarAutor(int $id, int $autorId)
    {
        try{
            $deletado = $this->livroAutorDAO->deletar($id, $autorId);

            if (!$deletado) {
                return response()->json(['message' => 'Associação não encontrada.'], 404);
            }
            return response()->json(['message' => 'Associação desfeita com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao associar livro ao Autor: ' . $e->getMessage()], 500);
        }
    }
}
