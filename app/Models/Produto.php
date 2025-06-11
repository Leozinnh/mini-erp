<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }

    protected $fillable = ['nome', 'preco'];
}
