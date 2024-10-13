<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivroAssunto extends Model
{
    use HasFactory;

    protected $table = 'livro_assunto';
    protected $fillable = ['Livro_codl', 'Assunto_codAs'];

    public function livro()
    {
        return $this->belongsTo(Livro::class, 'Livro_codl', 'Codl');
    }

    public function assunto()
    {
        return $this->belongsTo(Assunto::class, 'Assunto_codAs', 'codAs');
    }
}
