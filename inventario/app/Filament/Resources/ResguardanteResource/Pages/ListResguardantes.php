<?php

namespace App\Filament\Resources\ResguardanteResource\Pages;

use App\Filament\Resources\ResguardanteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResguardantes extends ListRecords
{
    protected static string $resource = ResguardanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
