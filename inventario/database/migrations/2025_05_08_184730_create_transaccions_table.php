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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id('id_transaccion');
            $table->foreignId('id_producto_sku')->constrained('producto_skus', 'id_producto_sku');
            $table->unsignedBigInteger('id_usuario')->nullable(); 
            $table->string('campo_modificado')->nullable(); 
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_transaccion')->nullable();
            $table->text('valor_anterior')->nullable(); // Valor antes del cambio
            $table->text('valor_nuevo')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('tipo'); 
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
