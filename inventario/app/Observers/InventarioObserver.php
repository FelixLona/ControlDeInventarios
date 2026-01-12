<?php

namespace App\Observers;

use App\Models\Inventario;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Auth;

class InventarioObserver
{
    /**
     * Handle the Inventario "created" event.
     */
    public function created(Inventario $inventario): void
    {
        //
    }

    /**
     * Handle the Inventario "updated" event.
     */
    public function updated(Inventario $inventario): void
    {
        $changes = $inventario->getChanges();
        
        
        foreach ($changes as $field => $newValue) {
            // Campos a registrar
            if (! in_array($field, ['id_ubicacion', 'id_estado', 'id_responsable'])) {
                continue;
            }

            // Traducción del campo
            $campoLabel = match ($field) {
                'id_ubicacion' => 'Ubicación',
                'id_estado' => 'Estado',
                'id_responsable' => 'Responsable',
                default => $field,
            };

            // Obtener nombres reales del valor anterior y nuevo
            $valorAnterior = $inventario->getOriginal($field);
            $valorNuevo = $newValue;

            // Obtener nombres legibles si existen
            $anteriorNombre = null;
            $nuevoNombre = null;

            if ($field === 'id_ubicacion') {
                $anteriorNombre = \App\Models\Ubicacion::find($valorAnterior)?->nombre;
                $nuevoNombre = \App\Models\Ubicacion::find($valorNuevo)?->nombre;
            }

            if ($field === 'id_estado') {
                $anteriorNombre = \App\Models\Estado::find($valorAnterior)?->nombre;
                $nuevoNombre = \App\Models\Estado::find($valorNuevo)?->nombre;
            }

            if ($field === 'id_responsable') {
                $anteriorNombre = \App\Models\Responsable::find($valorAnterior)?->nombre;
                $nuevoNombre = \App\Models\Responsable::find($valorNuevo)?->nombre;
            }

            // Guardar en transacciones
            Transaccion::create([
                'id_producto_sku' => $inventario->id_producto_sku,
                'id_usuario' => Auth::id(), // Requiere estar autenticado
                'tipo' => 'actualizacion',
                'campo_modificado' => $campoLabel,
                'valor_anterior' => $anteriorNombre ?? $valorAnterior,
                'valor_nuevo' => $nuevoNombre ?? $valorNuevo,
                'descripcion' => "Se actualizó el campo '$campoLabel'",
            ]);
        }
    }

    /**
     * Handle the Inventario "deleted" event.
     */
    public function deleted(Inventario $inventario): void
    {
        //
    }

    /**
     * Handle the Inventario "restored" event.
     */
    public function restored(Inventario $inventario): void
    {
        //
    }

    /**
     * Handle the Inventario "force deleted" event.
     */
    public function forceDeleted(Inventario $inventario): void
    {
        //
    }
}
