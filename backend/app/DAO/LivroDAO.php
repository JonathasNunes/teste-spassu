<?php

namespace App\DAO;

use App\Models\Livro;
use Illuminate\Support\Facades\DB;

class LivroDAO
{
    public function criar(array $data): Livro
    {
        return Livro::create($data);
    }

    public function buscarPorId(int $id): ?Livro
    {
        return Livro::where('Codl', $id)->first();
    }

    public function atualizar(int $id, array $data): bool
    {
        $livro = $this->buscarPorId($id);
        if ($livro) {
            return $livro->update($data);
        }
        return false;
    }

    public function deletar(int $id): bool
    {
        $livro = $this->buscarPorId($id);
        if ($livro) {
            return $livro->delete();
        }
        return false;
    }

    public function listar(): array
    {
        return Livro::all()->toArray();
    }

    /**
     * Obtém os dados da view de relatório de livros.
     */
    public function obterRelatorio()
    {
        return DB::table('view_relatorio_livros')->get();
    }
}
