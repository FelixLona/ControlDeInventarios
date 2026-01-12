<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estado;
class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
public function run()
{
    Estado::create(['nombre' => 'Disponible']);
    Estado::create(['nombre' => 'Asignado']);
    Estado::create(['nombre' => 'En reparación']);
}
}
