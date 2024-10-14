<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssuntoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assunto')->insert([
            [
                'Descricao' => 'Ficção Científica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Descricao' => 'Filosofia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Descricao' => 'História',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Descricao' => 'Biografia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Descricao' => 'Romance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
