<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use App\Models\Vehiculo;
use App\Models\Precio;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $zonas = Zona::all();
        $vehiculos = Vehiculo::all();
        $precios = Precio::with(['hotel.zona', 'vehiculo'])->get();

        return view('admin.configuracion.index', compact('zonas', 'vehiculos', 'precios'));
    }
}