<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resguardante;

class ResguardanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
public function run()
{
    Resguardante::create(['nombre' => 'Departamento de TI', 'descripcion' => 'Equipo de tecnología']);
}
}
