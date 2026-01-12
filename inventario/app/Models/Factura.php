<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
      protected $primaryKey = 'id_factura';

     protected $fillable =
      [
        'no_factura',
        'razon_social',
        'documento',
        'tipo',
        'observaciones',
        'fecha'

      ];

      public function inventario ()
      {
        return $this-> hasMany(Inventario::class,'id_factura');
      } 
  
}
