<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variacao extends Model
{
    use HasFactory;

    protected $table = 'variacoes';  // aqui o nome correto da tabela

    protected $fillable = ['produto_id', 'nome'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function estoque()
    {
        return $this->hasOne(Estoque::class);
    }
}
