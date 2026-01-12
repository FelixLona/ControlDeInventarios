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
        Schema::create('subclases', function (Blueprint $table) {
            $table->id('id_subclase');
            $table->unsignedBigInteger('id_clase')->nullable();
            $table->foreign('id_clase')
            ->references('id_clase')->on('clases');
            $table->string('clave');
            $table->string('nombre', 255);
            $table->tinyInteger('tipo_gasto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subclase');
    }
};
