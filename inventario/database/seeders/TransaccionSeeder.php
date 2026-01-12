<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaccion;

class TransaccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Transaccion::create([
        'id_producto_sku' => 1,
        'tipo' => 'Entrada',
        'descripcion' => 'Entrada inicial de inventario',
        'fecha_transaccion' => now(),
    ]);
}

}
