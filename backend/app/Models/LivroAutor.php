<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivroAutor extends Model
{
    use HasFactory;

    protected $table = 'livro_autor';

    protected $fillable = [
        'Livro_Codl',
        'Autor_CodAu',
    ];

    public function livro()
    {
        return $this->belongsTo(Livro::class, 'Livro_Codl', 'Codl');
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'Autor_CodAu', 'CodAu');
    }
}
