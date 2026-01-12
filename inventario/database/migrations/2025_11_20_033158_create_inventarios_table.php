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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id('id_inventario');


            $table->foreignId('id_producto_sku')->constrained('producto_skus', 'id_producto_sku');
            $table->integer('cantidad');
            $table->timestamps();

            $table->unsignedBigInteger('id_ubicacion')->nullable();
            $table->foreign('id_ubicacion')
                ->references('id_ubicacion')
                ->on('ubicaciones')
                ->onDelete('set null');


            $table->unsignedBigInteger('id_estado')->nullable();
            $table->foreign('id_estado')
                ->references('id_estado')
                ->on('estados')
                ->onDelete('set null');

            $table->string('cog')->nullable();
            $table->unsignedBigInteger('id_subclase')->nullable();
            $table->foreign('id_subclase')->references('id_subclase')->on('subclases')->onDelete('set null');
            $table->string('ur')->nullable();
            $table->string('ua')->nullable();
            $table->string('anno')->nullable();
            $table->unsignedBigInteger('numero_consecutivo')->nullable();
            $table->string('num_activo')->nullable();
            $table->float('importe', 10, 2)->default(0.00);
            $table->unsignedBigInteger('id_factura')->nullable();
            $table->foreign('id_factura')
                ->references('id_factura')
                ->on('facturas')
                ->onDelete('set null');
            $table->string('fecha')->nullable();
            $table->string('proveedor')->nullable();
            $table->string('modelo')->nullable();
            $table->string('num_serie')->nullable();
            $table->string('otras_especificaciones', 512)->nullable();
            $table->string('fuente_financiamiento')->nullable();
            $table->unsignedBigInteger('id_resguardante')->nullable();
            $table->foreign('id_resguardante')
                ->references('id_empleado')
                ->on('empleados')
                ->onDelete('set null');
            $table->unsignedBigInteger('id_responsable')->nullable();
            $table->foreign('id_responsable')
                ->references('id_empleado')
                ->on('empleados')
                ->onDelete('set null');

            $table->unsignedBigInteger('num_inventario')->nullable();
            $table->date('fecha_validacion')->nullable();
            $table->date('fecha_actualizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
