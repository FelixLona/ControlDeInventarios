<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Edificio;
class EdificioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Edificio::insert([
            ['nombre' => 'L', 'descripcion' => 'Edificio de Ing en Sistemas'],
            ['nombre' => 'B', 'descripcion' => 'Edificio de Ciencias Basicas'],

        ]);
    }
}
