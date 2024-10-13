<?php

namespace App\DAO;

use App\Models\LivroAutor;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LivroAutorDAO
{
    public function criar(array $data)
    {
        return LivroAutor::create($data);
    }

    public function buscarPorId($livroCodl, $autorCodAu)
    {
        $livroAutor = LivroAutor::where('Livro_Codl', $livroCodl)
            ->where('Autor_CodAu', $autorCodAu)
            ->first();

        if (!$livroAutor) {
            throw new ModelNotFoundException('LivroAutor não encontrado.');
        }

        return $livroAutor;
    }

    public function atualizar($livroCodl, $autorCodAu, array $data)
    {
        $livroAutor = $this->buscarPorId($livroCodl, $autorCodAu);
        return $livroAutor ? $livroAutor->update($data) : false;
    }

    public function deletar($livroCodl, $autorCodAu)
    {
        return LivroAutor::where('Livro_codl', $livroCodl)
                           ->where('Autor_CodAu', $autorCodAu)
                           ->delete() > 0; // Retorna true se algum registro foi deletado
    }

    public function listar()
    {
        return LivroAutor::all();
    }
}
