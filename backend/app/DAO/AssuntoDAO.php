<?php

namespace App\DAO;

use App\Models\Assunto;

class AssuntoDAO
{
    public function criar(array $data)
    {
        return Assunto::create($data);
    }

    public function buscarPorId($id)
    {
        return Assunto::find($id);
    }

    public function atualizar($id, array $data)
    {
        $assunto = $this->buscarPorId($id);
        return $assunto ? $assunto->update($data) : false;
    }

    public function deletar($id)
    {
        $assunto = $this->buscarPorId($id);
        return $assunto ? $assunto->delete() : false;
    }

    public function listar()
    {
        return Assunto::all();
    }
}
