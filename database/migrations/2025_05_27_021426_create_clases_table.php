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
        Schema::create('clases', function (Blueprint $table) {
            $table->id('id_clase');
            $table->unsignedBigInteger('id_subgrupo');
            $table->foreign('id_subgrupo')
            ->references('id_subgrupo')
            ->on('subgrupos');
            $table->string('clave');
            $table->string('nombre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clase');
    }
};
