<?php

namespace App\Filament\Resources\ProductoSKUResource\Pages;

use App\Filament\Resources\ProductoSKUResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductoSKU extends EditRecord
{
    protected static string $resource = ProductoSKUResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
