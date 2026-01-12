<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class DashboardController extends Controller
{
    public function index()
    {
        $productos = Producto::all(); // O paginado: Producto::paginate(10);
        return view('dashboard', compact('productos'));
    }
}
