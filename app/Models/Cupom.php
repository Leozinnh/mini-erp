<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;
    protected $table = 'cupons';

    protected $fillable = ['id', 'codigo', 'tipo', 'desconto', 'valor_minimo', 'validade'];

    
    public function isValido(): bool
    {
        return now()->lessThanOrEqualTo($this->validade);
    }
}
