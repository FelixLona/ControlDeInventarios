<?php

namespace App\Filament\Resources\ResguardanteResource\Pages;

use App\Filament\Resources\ResguardanteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResguardante extends EditRecord
{
    protected static string $resource = ResguardanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
