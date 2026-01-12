<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Inventario;
use Illuminate\Database\Eloquent\Model;
use App\Models\ArticuloInventario;
use App\Models\Grupo;
use App\Models\Subgrupo;
use App\Models\Clase;
use App\Models\Subclase;

class EditInventario extends EditRecord
{
    protected static string $resource = InventarioResource::class;

    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
{
    $grupo = Grupo::find($data['id_grupo']);
    $subgrupo = Subgrupo::find($data['id_subgrupo']);
    $clase = Clase::find($data['id_clase']);
    $subclase = Subclase::find($data['id_subclase']);

    $data['cog'] = collect([$grupo?->clave, $subgrupo?->clave, $clase?->clave, $subclase?->clave])->filter()->implode('.');
    
    $record->update($data);

    return $record;
}
}
