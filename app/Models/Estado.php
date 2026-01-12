<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
class Estado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estado';
    
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_estado');
    }
}
