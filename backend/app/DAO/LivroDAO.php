<?php

namespace App\DAO;

use App\Models\Livro;
use App\DAO\LivroAutorDAO;
use App\DAO\LivroAssuntoDAO;
use Illuminate\Support\Facades\DB;

class LivroDAO
{
    protected $livroAutorDAO;
    protected $livroAssuntoDAO;

    public function __construct()
    {
        $this->livroAutorDAO = new LivroAutorDAO();
        $this->livroAssuntoDAO = new LivroAssuntoDAO();
    }

    public function criar(array $data): Livro
    {   
        DB::beginTransaction();

        try {    
            if (!isset($data['Titulo']) || !isset($data['Editora'])) {
                throw new \Exception('Faltando dados obrigatórios: Titulo ou Editora');
            }

            // Dados obrigatórios
            $livroData = [
                'Titulo' => $data['Titulo'],
                'Editora' => $data['Editora'],
            ];

            // Mescla com os dados opcionais, filtrando os valores nulos
            $livroData = array_merge($livroData, array_filter([
                'Edicao' => $data['Edicao'] ?? null,
                'AnoPublicacao' => $data['AnoPublicacao'] ?? null,
                'preco' => $data['preco'] ?? null,
            ], function($value) {
                return !is_null($value);
            }));

            $livro = Livro::create($livroData);

            // Se houver 'Autor_CodAu', criar a relação com o autor
            if (isset($data['Autor_CodAu']) && is_array($data['Autor_CodAu'])) {
                foreach ($data['Autor_CodAu'] as $autorCodAu) {
                    $this->livroAutorDAO->criar([
                        'Livro_Codl' => $livro->Codl,
                        'Autor_CodAu' => $autorCodAu
                    ]);
                }
            }

            // Se houver 'Assunto_codAs', criar a relação com o assunto
            if (isset($data['Assunto_codAs']) && is_array($data['Assunto_codAs'])) {
                foreach ($data['Assunto_codAs'] as $assuntoCodAs) {
                    $this->livroAssuntoDAO->criar([
                        'Livro_codl' => $livro->Codl,
                        'Assunto_codAs' => $assuntoCodAs
                    ]);
                }
            }

            DB::commit();
            return $livro;
        
        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            throw new \Exception("Erro ao criar livro e suas relações: " . $e->getMessage());
        }
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
