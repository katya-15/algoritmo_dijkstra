<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Route;

class DashboarController extends Controller
{
    public function index()
    {
        // Datos para ciudades
        $ciudades = City::with('status')->active()->get();
        $totalCiudades = $ciudades->count();
        $ciudadesProgress = $totalCiudades > 0 ? min(100, round(($totalCiudades / 24) * 100)) : 0;

        //Datos para rutas
        $rutas = Route::where('status_id', 1)
            ->get();
        $totalRutas = $rutas->count();
        $rutasProgress = $totalRutas > 0 ? min(100, round(($totalRutas / 220) * 100)) : 0;


        // Agregamos la función round() para redondear al entero más cercano

        return view('welcome', [
            'ciudades' => $ciudades,
            'totalCiudades' => $totalCiudades,
            'ciudadesProgress' => $ciudadesProgress,
            'rutas' => $rutas,
            'totalRutas' => $totalRutas,
            'rutasProgress' => $rutasProgress
        ]);
    }
}
