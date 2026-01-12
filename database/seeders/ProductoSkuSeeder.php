<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductoSku;

class ProductoSkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    ProductoSku::create([
        'nombre' => 'Monitor Samsung 24"',
        'descripcion' => 'Pantalla Full HD',
    ]);

    ProductoSku::create([
        'nombre' => 'Resma de papel A4',
        'descripcion' => '500 hojas blancas',
    ]);
}
}
