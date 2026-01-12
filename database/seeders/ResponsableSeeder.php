<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Responsable;
class ResponsableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Responsable::create(['nombre' => 'Juan Pérez', 'telefono' => '22123323443', 'correo' => 'juan@correo.com']);

}
}
