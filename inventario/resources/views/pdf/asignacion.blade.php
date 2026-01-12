<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Asignación de Inventario</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 10px; text-align: left; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>

    <h1>Responsiva de Asignación de Inventario</h1>
    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>

    <h2>Datos del Responsable</h2>
    <p><strong>Nombre:</strong> {{ $responsable->nombre }}</p>
    <p><strong>Área / Departamento:</strong></p>

    <h2>Productos Asignados</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Ubicación</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td>{{ $producto['cantidad'] }}</td>
                    <td>{{ $producto['ubicacion'] }}</td>
                    <td>{{ $producto['estado'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><br><br></p>
        <p>Firma: ____________________________</p>
        <p>Nombre: {{ $responsable->nombre }}</p>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>