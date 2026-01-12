<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ArticuloInventario;
use App\Models\Inventario;
use App\Models\Grupo;
use App\Models\Subgrupo;
use App\Models\Clase;
use App\Models\Subclase;
use Illuminate\Database\Eloquent\Model;

class CreateInventario extends CreateRecord
{
    protected static string $resource = InventarioResource::class;
   
    protected function handleRecordCreation(array $data): Model
{
    $grupo = Grupo::find($data['id_grupo']);
    $subgrupo = Subgrupo::find($data['id_subgrupo']);
    $clase = Clase::find($data['id_clase']);
    $subclase = Subclase::find($data['id_subclase']);

    $data['cog'] = collect([$grupo?->clave, $subgrupo?->clave, $clase?->clave, $subclase?->clave])->filter()->implode('.');
    
    $record = static::getModel()::create($data);

    return $record;
}

}
