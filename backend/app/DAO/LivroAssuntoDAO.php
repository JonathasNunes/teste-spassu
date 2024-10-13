<?php

namespace App\DAO;

use App\Models\LivroAssunto;

class LivroAssuntoDAO
{
    public function criar(array $data)
    {
        return LivroAssunto::create($data);
    }

    public function buscarPorId($livroCodl, $assuntoCodAs)
    {
        return LivroAssunto::where('Livro_codl', $livroCodl)
            ->where('Assunto_codAs', $assuntoCodAs)
            ->first();
    }

    public function atualizar($livroCodl, $assuntoCodAs, array $data)
    {
        $livroAssunto = $this->buscarPorId($livroCodl, $assuntoCodAs);
        return $livroAssunto ? $livroAssunto->update($data) : false;
    }

    public function deletar($livroCodl, $assuntoCodAs)
    {
        return LivroAssunto::where('Livro_codl', $livroCodl)
                           ->where('Assunto_codAs', $assuntoCodAs)
                           ->delete() > 0; // Retorna true se algum registro foi deletado
    }
    
    public function listar()
    {
        return LivroAssunto::with(['livro', 'assunto'])->get();
    }
}
