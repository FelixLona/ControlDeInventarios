<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ubicacion;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Ubicacion::create(['nombre' => 'Almacén Central', 'descripcion' => 'Zona principal']);
    Ubicacion::create(['nombre' => 'Bodega Secundaria', 'descripcion' => 'Zona secundaria']);
}
}
