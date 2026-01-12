<?php

namespace App\Filament\Resources\ProductoSKUResource\Pages;

use App\Filament\Resources\ProductoSKUResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductoSKUS extends ListRecords
{
    protected static string $resource = ProductoSKUResource::class;
    protected ?string $heading = 'Inventario de Productos';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
