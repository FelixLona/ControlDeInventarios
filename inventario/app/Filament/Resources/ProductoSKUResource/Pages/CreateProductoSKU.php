<?php

namespace App\Filament\Resources\ProductoSKUResource\Pages;

use App\Filament\Resources\ProductoSKUResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductoSKU extends CreateRecord
{
    protected static string $resource = ProductoSKUResource::class;

    public function getTitle(): string{
        return 'Nuevo Producto';
    }
}
