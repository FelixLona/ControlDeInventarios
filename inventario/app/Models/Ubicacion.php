<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $primaryKey = 'id_ubicacion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_aula'

    ];

    public function inventario()
    {
        return $this->hasone(Inventario::class, 'id_ubicacion');
    }
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }
    
}
