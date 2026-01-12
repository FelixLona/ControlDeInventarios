<?php

namespace App\Exports;

use App\Models\Transaccion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaccionesExport implements FromCollection,  WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return Transaccion::all();
        return Transaccion::with(['producto', 'usuario'])->get();
    }
    public function map($row): array
    {
        // Puedes mejorar con nombres reales si usas relaciones
        return [
            $row->producto->nombre ?? 'Producto desconocido',
            match ($row->campo_modificado) {
                'id_ubicacion' => 'Ubicación',
                'id_estado' => 'Estado',
                'id_responsable' => 'Responsable',
                default => $row->campo_modificado,
            },
            $row->valor_anterior_nombre ?? $row->valor_anterior,
            $row->valor_nuevo_nombre ?? $row->valor_nuevo,
            $row->usuario->name ?? 'Sin usuario',
            \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Campo Modificado',
            'Valor Anterior',
            'Valor Nuevo',
            'Realizado por',
            'Fecha y Hora',
        ];
    }

}
