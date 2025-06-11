<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    use HasFactory;
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }
    protected $fillable = ['pedido_id', 'variacao_id', 'quantidade', 'preco_unitario'];
}
