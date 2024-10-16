<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\LivroController;
    
    Route::get('/', function () {
        return view('welcome');
    });

    // Rotas para o recurso Autor
    Route::prefix('autores')->group(function () {
        // Lista todos os autores
        Route::get('/', [AutorController::class, 'index'])->name('autores.index');

        // Cria um novo autor
        Route::post('/', [AutorController::class, 'store'])->name('autores.store');

        // Exibe um autor específico
        Route::get('/{id}', [AutorController::class, 'show'])->name('autores.show');

        // Atualiza um autor específico
        Route::put('/', [AutorController::class, 'update'])->name('autores.update');

        // Remove um autor específico
        Route::delete('/{id}', [AutorController::class, 'destroy'])->name('autores.destroy');

        // Associa um livro a um autor
        Route::post('/{autorId}/livros', [AutorController::class, 'associarLivro'])->name('autores.associarLivro');

        // Remove a associação entre um autor e um livro
        Route::delete('/{autorId}/livros/{livroCodl}', [AutorController::class, 'desassociarLivro'])->name('autores.desassociarLivro');

        // Busca autor pelo nome
        Route::get('/buscar/nome/{nome}', [AutorController::class, 'buscarPorNome'])->name('autores.buscarPorNome');
    });

    Route::prefix('assuntos')->group(function () {

        Route::get('/', [AssuntoController::class, 'index'])->name('assuntos.index');

        // Route::post('/', [AssuntoController::class, 'store'])->name('assuntos.store');

        Route::get('/{id}', [AssuntoController::class, 'show'])->name('assuntos.show');

        Route::put('/', [AssuntoController::class, 'update'])->name('assuntos.update');

        Route::delete('/{id}', [AssuntoController::class, 'destroy'])->name('assuntos.destroy');

        // Rotas para associar e desassociar Livros e Assuntos
        Route::post('/{assuntoId}/livros', [AssuntoController::class, 'associarLivro'])->name('assuntos.associarLivro');

        Route::delete('/{assuntoId}/livros/{livroCodl}', [AssuntoController::class, 'desassociarLivro'])->name('assuntos.desassociarLivro');
    });

    Route::prefix('livros')->group(function () {
        Route::get('/', [LivroController::class, 'index']); // Listar todos os livros

        Route::post('/', [LivroController::class, 'store']); // Criar um novo livro
        
        Route::get('/{id}', [LivroController::class, 'show']); // Mostrar um livro específico
        
        Route::put('/', [LivroController::class, 'update']); // Atualizar um livro existente
        
        Route::delete('/{id}', [LivroController::class, 'destroy']); // Deletar um livro

        // Rotas para associar e desassociar livros
        Route::post('/{id}/assuntos', [LivroController::class, 'associarAssunto']);
        
        Route::delete('/{id}/assunto/{assuntoId}', [LivroController::class, 'desassociarAssunto']);
        
        Route::post('/{id}/autores', [LivroController::class, 'associarAutor']);
        
        Route::delete('/{id}/autor/{autorId}', [LivroController::class, 'desassociarAutor']);
    });

    // Route::post('/assuntos', [AssuntoController::class, 'store']);//->name('assuntos.store');

    Route::get('/relatorio', [LivroController::class, 'gerarRelatorio']);

    Route::post('/assuntos', [AssuntoController::class, 'store'])->middleware('cors');