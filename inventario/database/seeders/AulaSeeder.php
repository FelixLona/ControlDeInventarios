<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aula;
use App\Models\Edificio;

class AulaSeeder extends Seeder
{
    public function run(): void
    {

        if (Edificio::count() === 0) {
            $this->command->warn('No hay edificios registrados.');
            return;
        }


        Aula::insert([
            ['nombre' => 'LIA', 'descripcion' => 'Aula de Inteligencia Artificial', 'id_edificio' => 1],
            ['nombre' => 'RDE', 'descripcion' => 'Aula de Redes', 'id_edificio' => 1],
            ['nombre' => 'B01', 'descripcion' => 'Aula 1 de Basicas', 'id_edificio' =>2],
            ['nombre' => 'B02', 'descripcion' => 'Aula 2 de Basicas', 'id_edificio'=>2],
        ]);


        $this->command->info('Aulas creadas correctamente.');
    }
}
