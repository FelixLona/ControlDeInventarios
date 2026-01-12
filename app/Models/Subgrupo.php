<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subgrupo extends Model
{
    protected $table = 'subgrupos';
    protected $primaryKey = 'id_subgrupo';
    public $timestamps = true;
    protected $fillable = ['clave', 'nombre', 'id_grupo'];
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }
    public function clases()
    {
        return $this->hasMany(Clase::class, 'id_subgrupo');
    }
}
