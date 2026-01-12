<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grupo extends Model
{
    protected $primaryKey = 'id_grupo'; 
    public $timestamps = true;
    protected $fillable = ['clave', 'nombre'];
    public function subgrupos()
{
    return $this->hasMany(Subgrupo::class, 'id_grupo');
}
}
