<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('autor')->insert([
            [
                'Nome' => 'Isaac Asimov',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nome' => 'Friedrich Nietzsche',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nome' => 'JRR Tolkien',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nome' => 'George Orwell',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nome' => 'Jane Austen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

