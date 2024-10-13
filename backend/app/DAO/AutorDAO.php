<?php

namespace App\DAO;

use App\Models\Autor;

class AutorDAO
{
    public function criar(array $data)
    {
        return Autor::create($data);
    }

    public function buscarPorId($id)
    {
        return Autor::find($id);
    }

    public function atualizar($id, array $data)
    {
        $autor = $this->buscarPorId($id);
        return $autor ? $autor->update($data) : false;
    }

    public function deletar($id)
    {
        $autor = $this->buscarPorId($id);
        return $autor ? $autor->delete() : false;
    }

    public function listar()
    {
        return Autor::all();
    }
}
