<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
     protected $table = 'aulas';
    protected $primaryKey = 'id_aula';
    protected $fillable = ['nombre', 'descripcion','id_edificio'];

    public function edificio()
    {
        return $this -> belongsTo(Edificio::class,'id_edificio', 'id_edificio');
    }
    public function ubicacion()
    {
        return $this -> hasMany(Ubicacion::class, 'id_aula','id_aula');
    }
}
