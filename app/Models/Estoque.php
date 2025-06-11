<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;
    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }

    protected $fillable = ['variacao_id', 'quantidade'];
}
