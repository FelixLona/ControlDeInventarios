<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class clase extends Model
{

    protected $table = 'clases';
    protected $primaryKey = 'id_clase';
    public $timestamps = true;
    protected $fillable = ['clave', 'nombre', 'id_subgrupo'];
    public function subgrupo()
{
    return $this->belongsTo(Subgrupo::class, 'id_subgrupo');
}
public function subclases()
{
    return $this->hasMany(Subclase::class, 'id_clase');
}
}
