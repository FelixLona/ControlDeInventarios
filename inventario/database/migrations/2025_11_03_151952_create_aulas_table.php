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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id('id_aula');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            // Crea una columna nueva que almacenará la clave foránea.
            // Es de tipo entero grande sin signo (unsignedBigInteger), compatible con el tipo de 'id' en la tabla foranea.
            // Se agrega ->nullable() para permitir que la columna acepte valores nulos, 
            // es decir, el registro puede existir aunque no tenga una aula asociada. En este caso por si 
            //existe una bodega que no este asociada a un edificio
            $table->unsignedBigInteger('id_edificio')->nullable();

            // Define una clave foránea sobre la columna 'id_aula'.
            $table->foreign('id_edificio')
                // Indica que esta clave foránea hace referencia a la columna 'id_edificios' de la tabla relacionada.
                ->references('id_edificio')
                // Especifica la tabla con la que se establece la relación.
                
                ->on('edificios')
                // Define el comportamiento al eliminar el registro relacionado en la tabla 'edificios'.
                // 'set null' significa que, si se borra el edificio asociada, el campo 'id_edificios' se establecerá en NULL.
                // Esto solo funciona porque la columna fue declarada como nullable.
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
