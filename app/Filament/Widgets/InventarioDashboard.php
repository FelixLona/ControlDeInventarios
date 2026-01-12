<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\ProductoSku;
use App\Models\Responsable;
use App\Models\Ubicacion;
use Filament\Widgets\StatsOverviewWidget\Card;

class InventarioDashboard extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total SKUs', ProductoSku::count()),
            Card::make('Stock Total', Inventario::sum('cantidad')),
            Card::make('Ubicaciones', Ubicacion::count()),
            Card::make('Responsables', Responsable::count()),
        ];
    }
}
