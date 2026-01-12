<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventario;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Inventario::create([
        'id_producto_sku' => 1,
        'cantidad' => 10,
        'fecha_actualizacion' => now(),
    ]);

    Inventario::create([
        'id_producto_sku' => 2,
        'cantidad' => 50,
        'fecha_actualizacion' => now(),
    ]);
}
}
