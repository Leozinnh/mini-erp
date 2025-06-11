<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->decimal('subtotal', 10, 2);      // valor do subtotal do pedido
            $table->decimal('frete', 10, 2);         // valor do frete
            $table->string('cep', 9);                 // CEP do endereço
            $table->string('endereco');               // endereço completo (logradouro)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
