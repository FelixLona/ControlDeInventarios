<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
class Responsable extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_responsable';

    protected $fillable = [
        'nombre',
        'correo',
        'telefono'
    ];

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_responsable');
    }
}
