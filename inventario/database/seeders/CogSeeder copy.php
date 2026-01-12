<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Subgrupo;
use App\Models\Clase;
use App\Models\Subclase;

class CogSeeder extends Seeder
{
    private array $tiposGastoMap = [
        'GASTO CORRIENTE' => '1',
        'GASTO DE CAPITAL' => '2',
        'AMORTIZACIÓN DE LA DEUDA Y DISMINUCIÓN DE PASIVOS' => '3',
        'PENSIONES Y JUBILACIONES' => '4',
        'PARTICIPACIONES' => '5',
        'TRANSITORIA' => '6'
    ];

    private function crearEstructuraBase()
{
    // Grupos base
    $grupos = [
        '1' => 'Servicios Personales',
        '2' => 'Materiales y Suministros',
        '3' => 'Servicios Generales',
        '4' => 'Transferencias, Asignaciones, Subsidios y Otras Ayudas',
        '5' => 'Mobiliario, Equipo y Material de Administración',
        '6' => 'Obras, Adquisición de Inmuebles e Intangibles',
        '7' => 'Inversiones Financieras',
        '8' => 'Aportaciones y Participaciones',
        '9' => 'Servicio de la Deuda Pública'
    ];

    foreach ($grupos as $clave => $nombre) {
        $grupo = Grupo::firstOrCreate(['clave' => $clave], ['nombre' => $nombre]);
        \Log::info("Grupo procesado", ['clave' => $clave, 'nombre' => $nombre]);

        // Subgrupos base comunes (ajusta según tu CSV)
        for ($i = 1; $i <= 9; $i++) {
            if ($this->subgrupoExisteEnCSV($clave . $i)) {
                Subgrupo::firstOrCreate(
                    ['clave' => (string)$i, 'id_grupo' => $grupo->id_grupo],
                    ['nombre' => "Subgrupo $i"]
                );
                \Log::info("Subgrupo procesado", ['clave' => $i, 'grupo' => $clave]);
            }
        }
    }
}
private function subgrupoExisteEnCSV(string $subgrupoClave): bool
{
    // Lista de subgrupos presentes en el CSV (puedes completarla)
    $existentes = [
        '11', '12', '13', '14', '15', '16', '17', '18', '19',
        '21', '22', '23', '24', '25', '26', '27', '28', '29',
        '31', '32', '33', '34', '35', '36', '37', '38', '39',
        '41', '42', '43', '44', '45', '46', '47', '48', '49',
        '51', '52', '53', '54', '55', '56', '57', '58', '59',
        '61', '62', '63', '64', '65', '66', '67', '68', '69',
        '71', '72', '73', '74', '75', '76', '77', '78', '79',
        '81', '82', '83', '84', '85', '86', '87', '88', '89',
        '91', '92', '93', '94', '95', '96', '97', '98', '99',
    ];

    return in_array($subgrupoClave, $existentes);
}

    public function run()
    {
        $this->crearEstructuraBase();
        $this->command->info('Importando datos del COG desde CSV...');

        $path = database_path('seeders/cog.csv');
        if (!file_exists($path)) {
            $this->command->error("Archivo cog.csv no encontrado en: " . $path);
            return;
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->command->error("No se pudo abrir el archivo cog.csv");
            return;
        }

        // Leer encabezado
        fgetcsv($handle, 1000, ',');

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $clave = trim($row[0] ?? '');
            $valor = trim($row[1] ?? '');
            $tipoGastoTexto = trim($row[2] ?? '');

            if (empty($clave) || !is_numeric($clave)) {
                \Log::warning('Clave inválida o vacía', ['línea' => $row]);
                continue;
            }

            \Log::debug('Procesando línea:', compact('clave', 'valor', 'tipoGastoTexto'));

            $nivel = strlen($clave);
            $nombre = $valor;

            preg_match_all('/$(.*?)$/', $tipoGastoTexto, $matches);
            $tipoGasto = null;
            if (!empty($matches[1][0])) {
                $tipoGasto = $this->mapTipoGasto($matches[1][0]);
            }

            switch ($nivel) {
                case 4:
                    $this->procesarSubclase($clave, $nombre, $tipoGasto);
                    break;

                case 3:
                    $this->procesarClase($clave, $nombre);
                    break;

                case 2:
                    $this->procesarSubgrupo($clave, $nombre);
                    break;

                case 1:
                    $this->procesarGrupo($clave, $nombre);
                    break;
            }
        }

        fclose($handle);
        $this->command->info('COG importado exitosamente.');
    }

    private function procesarGrupo(string $clave, string $nombre)
    {
        $grupo = Grupo::updateOrCreate(
            ['clave' => $clave],
            ['nombre' => $nombre]
        );

        \Log::info("Grupo procesado", [
            'clave' => $clave,
            'nombre' => $nombre,
            'creado' => $grupo->wasRecentlyCreated ? 'Nuevo' : 'Existente'
        ]);
    }

    private function procesarSubgrupo(string $clave, string $nombre)
    {
        $grupoClave = substr($clave, 0, 1);
        $subgrupoClave = substr($clave, 1, 1);

        $grupo = Grupo::where('clave', $grupoClave)->first();
        if (!$grupo) {
            \Log::warning("Grupo no encontrado", ['clave' => $grupoClave]);
            return;
        }

        $subgrupo = Subgrupo::updateOrCreate(
            ['clave' => $subgrupoClave, 'id_grupo' => $grupo->id_grupo],
            ['nombre' => $nombre]
        );

        \Log::info("Subgrupo procesado", [
            'clave' => $subgrupoClave,
            'nombre' => $nombre,
            'grupo_id' => $grupo->id_grupo,
            'creado' => $subgrupo->wasRecentlyCreated ? 'Nuevo' : 'Existente'
        ]);
    }

    private function procesarClase(string $clave, string $nombre)
    {
        $grupoClave = substr($clave, 0, 1);
        $subgrupoClave = substr($clave, 1, 1);
        $claseClave = $this->getClaseClave($clave);

        $grupo = Grupo::where('clave', $grupoClave)->first();
        if (!$grupo) {
            \Log::warning("Grupo no encontrado", ['clave' => $grupoClave]);
            return;
        }

        $subgrupo = Subgrupo::where('clave', $subgrupoClave)
                            ->where('id_grupo', $grupo->id_grupo)
                            ->first();
        if (!$subgrupo) {
            \Log::warning("Subgrupo no encontrado", ['clave' => $subgrupoClave, 'grupo_id' => $grupo->id_grupo]);
            return;
        }

        $clase = Clase::updateOrCreate(
            ['clave' => $claseClave, 'id_subgrupo' => $subgrupo->id_subgrupo],
            ['nombre' => $nombre]
        );

        \Log::info("Clase procesada", [
            'clave' => $claseClave,
            'nombre' => $nombre,
            'subgrupo_id' => $subgrupo->id_subgrupo,
            'creado' => $clase->wasRecentlyCreated ? 'Nuevo' : 'Existente'
        ]);
    }

    private function procesarSubclase(string $clave, string $nombre, ?string $tipoGasto)
    {
        $grupoClave = substr($clave, 0, 1);
        $subgrupoClave = substr($clave, 1, 1);
        $claseClave = $this->getClaseClave($clave);
        $subclaseClave = substr($clave, 3, 1);

        $grupo = Grupo::where('clave', $grupoClave)->first();
        if (!$grupo) {
            \Log::warning("Grupo no encontrado", ['clave' => $grupoClave]);
            return;
        }

        $subgrupo = Subgrupo::where('clave', $subgrupoClave)
                            ->where('id_grupo', $grupo->id_grupo)
                            ->first();
        if (!$subgrupo) {
            \Log::warning("Subgrupo no encontrado", ['clave' => $subgrupoClave, 'grupo_id' => $grupo->id_grupo]);
            return;
        }

        $clase = Clase::where('clave', $claseClave)
                      ->where('id_subgrupo', $subgrupo->id_subgrupo)
                      ->first();
        if (!$clase) {
            \Log::warning("Clase no encontrada", ['clave' => $claseClave, 'subgrupo_id' => $subgrupo->id_subgrupo]);
            return;
        }

        $subclase = Subclase::updateOrCreate(
            ['clave' => $subclaseClave, 'id_clase' => $clase->id_clase],
            ['nombre' => $nombre, 'tipo_gasto' => $tipoGasto]
        );

        \Log::info("Subclase procesada", [
            'clave' => $subclaseClave,
            'nombre' => $nombre,
            'clase_id' => $clase->id_clase,
            'tipo_gasto' => $tipoGasto,
            'creado' => $subclase->wasRecentlyCreated ? 'Nuevo' : 'Existente'
        ]);
    }
    private function getClaseClave(string $clave): string
{
    $nivel = strlen($clave);

    if ($nivel === 4) {
        return substr($clave, 2, 1); // nivel 4 → usa tercera posición
    }

    if ($nivel === 3) {
        return substr($clave, 2, 1); // nivel 3 → usa tercera posición también
    }

    return substr($clave, 0, 1); // nivel 2 o 1
}

    private function mapTipoGasto(?string $texto): ?string
    {
        if (!$texto) return null;

        $tipos = explode(',', str_replace(['[', ']'], '', $texto));
        $result = [];

        foreach ($tipos as $tipo) {
            $clean = trim(strtoupper($tipo));
            if (isset($this->tiposGastoMap[$clean])) {
                $result[] = $this->tiposGastoMap[$clean];
            }
        }

        return !empty($result) ? implode('-', array_unique($result)) : null;
    }
}