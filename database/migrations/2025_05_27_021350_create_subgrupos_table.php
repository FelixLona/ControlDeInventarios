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
        Schema::create('subgrupos', function (Blueprint $table) {
            $table->id('id_subgrupo');
            $table->unsignedBigInteger('id_grupo');
            $table->foreign('id_grupo')
            ->references('id_grupo')
            ->on('grupos');
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
        Schema::dropIfExists('subgrupo');
    }
};
