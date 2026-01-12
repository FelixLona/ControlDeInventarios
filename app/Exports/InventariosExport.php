<?php

namespace App\Exports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class InventariosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $inventarios;

    public function __construct(Collection $inventarios)
    {
        $this->inventarios = $inventarios;
    }

    public function collection()
    {
        return $this->inventarios;
    }

    public function map($row): array
    {
        return [
            'Producto' => $row->producto->nombre,
            'Cantidad' => $row->cantidad,
            'Ubicación' => $row->ubicacion?->nombre ?? '',
            'Estado' => $row->estado?->nombre ?? '',
            'Responsable' => $row->responsable?->nombre ?? '',
            'Fecha actualización' => $row->fecha_actualizacion,
        ];
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Cantidad',
            'Ubicación',
            'Estado',
            'Responsable',
            'Fecha de Actualización'
        ];
    }
}
