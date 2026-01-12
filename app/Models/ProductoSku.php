<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use App\Models\Inventario;
use App\Models\Transaccion;
class ProductoSku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_producto_sku';
    protected $fillable = ['nombre', 'descripcion', 'codigo_lpn'];

    

    public function inventario()
    {
        return $this->hasOne(Inventario::class, 'id_producto_sku');
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_producto_sku');
    }
}
    

