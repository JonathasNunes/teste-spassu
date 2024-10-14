<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('livro')->insert([
            [
                'Titulo' => 'O Senhor dos Anéis: A Sociedade do Anel',
                'Editora' => 'HarperCollins',
                'Edicao' => 1,
                'AnoPublicacao' => '1954',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Titulo' => 'O Hobbit',
                'Editora' => 'HarperCollins',
                'Edicao' => 3,
                'AnoPublicacao' => '1937',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Titulo' => '1984',
                'Editora' => 'Companhia das Letras',
                'Edicao' => 5,
                'AnoPublicacao' => '1949',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Titulo' => 'Dom Quixote',
                'Editora' => 'Martin Claret',
                'Edicao' => 2,
                'AnoPublicacao' => '1605',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Titulo' => 'O Pequeno Príncipe',
                'Editora' => 'Agir',
                'Edicao' => 1,
                'AnoPublicacao' => '1943',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
