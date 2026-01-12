<?php

namespace App\Observers;

use App\Models\Inventario;
use Illuminate\Support\Facades\Log;


class TestInventarioObserver
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
    public function updated(Inventario $inventario)
    {
        Log::info("Observer disparado", [
            'id_inventario' => $inventario->id_inventario,
            'changes' => $inventario->getChanges()
        ]);
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
