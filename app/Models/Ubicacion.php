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
    ];

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_ubicacion');
    }
}
