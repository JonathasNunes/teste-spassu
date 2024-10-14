<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Http\Request;
use App\DAO\AutorDAO;
use App\DAO\LivroAutorDAO;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class AutorController extends Controller
{
    protected $autorDAO;
    protected $livroAutorDAO;

    public function __construct(AutorDAO $autorDAO, LivroAutorDAO $livroAutorDAO)
    {
        $this->autorDAO = $autorDAO;
        $this->livroAutorDAO = $livroAutorDAO;
    }

    /**
     * Lista todos os autores.
     */
    public function index()
    {
        try {
            $autores = $this->autorDAO->listar();
            return response()->json($autores, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao buscar autores'], 500);
        }
    }

    /**
     * Cria um novo autor.
     */
    public function store(AutorRequest $request)
    {
        try {
            // Validação será feita pelo AutorRequest
            $dadosValidados = $request->validated();

            // Criação do Autor
            $autor = $this->autorDAO->criar($dadosValidados);
            return response()->json($autor, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao criar autor'], 500);
        }
    }

    /**
     * Exibe um autor específico.
     */
    public function show($id)
    {
        try {
            $autor = $this->autorDAO->buscarPorId($id);
            if (!$autor) {
                return response()->json(['error' => 'Autor não encontrado'], 404);
            }
            return response()->json($autor);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao buscar autor'], 500);
        }
    }

    /**
     * Atualiza um autor específico.
     */
    public function update(AutorRequest $request)
    {
        try {
            $dadosValidados = $request->validated();

            $autor = $this->autorDAO->atualizar($request['CodAu'], $dadosValidados);

            if (!$autor) {
                return response()->json(['error' => 'Autor não encontrado'], 404);
            }
            return response()->json($autor, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao atualizar autor'], 500);
        }
    }

    /**
     * Remove um autor específico.
     */
    public function destroy($id)
    {
        try {
            $response = $this->autorDAO->deletar($id);
            if (!$response) {
                return response()->json(['error' => 'Autor não encontrado'], 404);
            }
            return response()->json(['message' => 'Autor deletado com sucesso'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao deletar autor'], 500);
        }
    }

    /**
     * Associa livros a um autor.
     */
    public function associarLivro(Request $request, $autorId)
    {
        $request->validate([
            'livro_codl' => 'required|integer|exists:livro,Codl',
        ]);

        try {
            // Criar a associação
            $this->livroAutorDAO->criar([
                'Livro_Codl' => $request->livro_codl,
                'Autor_CodAu' => $autorId,
            ]);
            return response()->json(['message' => 'Livro associado ao autor com sucesso.'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao associar livro ao autor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove a associação entre um autor e um livro.
     */
    public function desassociarLivro($autorId, $livroCodl)
    {
        try {
            $deleted = $this->livroAutorDAO->deletar($livroCodl, $autorId);
            if ($deleted) {
                return response()->json(['message' => 'Livro desassociado do autor com sucesso.']);
            } else {
                return response()->json(['message' => 'Associação não encontrada.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao desassociar livro do autor: ' . $e->getMessage()], 500);
        }
    }

    // Método para buscar autor pelo nome
    public function buscarPorNome(Request $request)
    {
        $nome = $request->input('nome');
        return $this->autorDAO->buscarPorNome($nome);
    }
}
