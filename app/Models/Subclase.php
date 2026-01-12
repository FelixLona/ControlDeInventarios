<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subclase extends Model
{
    protected $table = 'subclases';
    protected $primaryKey = 'id_subclase';
    public $timestamps = true;
    protected $fillable = ['clave', 'nombre', 'tipo_gasto', 'id_clase']; 
    public function clase()
{
    return $this->belongsTo(Clase::class, 'id_clase');
}
}
