<?php

namespace App\DAO;

use App\Models\Livro;

class LivroDAO
{
    public function criar(array $data): Livro
    {
        return Livro::create($data);
    }

    public function buscarPorId(int $id): ?Livro
    {
        return Livro::find($id);
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
}
