<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edificio extends Model
{

    protected $table = 'edificios';
    protected $primaryKey = 'id_edificio';
    protected $fillable = ['nombre', 'descripcion'];

    public function aula()
    {
        return $this -> hasMany(Aula::class, 'id_edificio', 'id_edificio');
    }
}
