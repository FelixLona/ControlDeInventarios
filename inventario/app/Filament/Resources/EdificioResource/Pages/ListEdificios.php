<?php

namespace App\Filament\Resources\EdificioResource\Pages;

use App\Filament\Resources\EdificioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEdificios extends ListRecords
{
    protected static string $resource = EdificioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
