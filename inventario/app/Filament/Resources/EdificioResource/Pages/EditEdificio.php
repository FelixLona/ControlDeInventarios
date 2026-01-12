<?php

namespace App\Filament\Resources\EdificioResource\Pages;

use App\Filament\Resources\EdificioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEdificio extends EditRecord
{
    protected static string $resource = EdificioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
