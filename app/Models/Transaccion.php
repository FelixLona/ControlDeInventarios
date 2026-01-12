<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductoSku;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
class Transaccion extends Model
{
    use HasFactory;
    protected $table = 'transacciones';
    protected $primaryKey = 'id_transaccion';
    protected $fillable = [
        'id_producto_sku',
        'id_usuario',
        'tipo',
        'cantidad',
        'descripcion',
        'ubicacion',
        'valor_anterior',
        'valor_nuevo',
        'campo_modificado'
    ];

    public function producto()
    {
        return $this->belongsTo(ProductoSku::class, 'id_producto_sku');
    }
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario');
    }
}
