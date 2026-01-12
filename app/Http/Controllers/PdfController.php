<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Responsable;
use App\Models\Inventario;

class PdfController extends Controller
{
    public function generarResponsiva(Request $request, $responsableId)
    {
        // Obtener responsable
        $responsable = Responsable::findOrFail($responsableId);

        // Obtener productos asignados a este responsable
        $inventarios = Inventario::where('id_responsable', $responsableId)->get();

        // Preparar datos para la vista
        $productos = $inventarios->map(function ($item) {
            return [
                'nombre' => $item->producto->nombre,
                'cantidad' => $item->cantidad,
                'ubicacion' => $item->ubicacion?->nombre,
                'estado' => $item->estado?->nombre,
            ];
        });

        // Pasar a la vista
        $data = [
            'responsable' => $responsable,
            'productos' => $productos,
        ];

        // Generar PDF
        $pdf = Pdf::loadView('pdf.asignacion', $data);
        
        return $pdf->download("responsiva_{$responsable->nombre}_" . now()->format('Ymd_His') . ".pdf");
    }
}
