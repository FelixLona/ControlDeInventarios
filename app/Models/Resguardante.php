<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Resguardante extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_resguardante';
    protected $fillable = ['nombre', 'correo', 'telefono'];
    

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_resguardante');
    }
}
