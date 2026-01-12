<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $primaryKey = 'id_empleado';
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
    ];
      public function inventariosComoResponsable()
    {
        return $this->hasMany(Inventario::class, 'id_responsable', 'id_empleado');
    }
}
