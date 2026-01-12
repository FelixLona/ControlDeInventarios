<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProductoSku;
use App\Models\Factura;
use App\Models\Ubicacion;
use App\Models\Responsable;
use App\Models\Estado;
use App\Models\User;
use App\Models\ArticuloInventario;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Subgrupo;
use App\Models\Clase;
use App\Models\Subclase;
use App\Models\Empleado;

class Inventario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_inventario';

    protected $fillable = [
        'id_producto_sku',
        'cantidad',
        'fecha_actualizacion',
        'id_responsable',
        'id_ubicacion',
        'id_estado',
        'id_subclase',
        'cog',
        'importe',
        'id_factura'
    ];
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'id_factura');
    }

    public function producto()
    {
        return $this->belongsTo(ProductoSku::class, 'id_producto_sku');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion');
    }


 
public function responsable()
{
    return $this->belongsTo(Empleado::class, 'id_responsable', 'id_empleado');
}

    public function resguardante()
    {
        return $this->belongsTo(Empleado::class, 'id_resguardante', 'id_empleado');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
    public function estado()
    {
        return $this->belongsTo(\App\Models\Estado::class, 'id_estado');
    }
    /*
public function articulos()
{
    return $this->hasMany(ArticuloInventario::class, 'id_inventario');
}*/


    public function subclase()
    {
        return $this->belongsTo(\App\Models\Subclase::class, 'id_subclase');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->fecha_actualizacion = now();
        });
    }
}
