<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ubicacion;
use App\Models\Aula;

class UbicacionSeeder extends Seeder
{
    
    public function run(): void
    {
        
        if (Aula::count() === 0) {
            $this->command->warn('No hay aulas registradas.');
            return;
        }

         Ubicacion::insert([
            ['nombre' => 'Estante1', 'descripcion' => 'Estante1', 'id_aula' => 2],
            ['nombre' => 'Mesa', 'descripcion' => 'Escritorio Profesor', 'id_aula' => 3],
            ['nombre' => 'Fila1', 'descripcion' => 'Fila 1', 'id_aula' =>1],
            ['nombre' => 'Fila2', 'descripcion' => 'Fila 2', 'id_aula'=>4],
        ]);

        $this->command->info('Ubicaciones creadas correctamente.');
    }
}
