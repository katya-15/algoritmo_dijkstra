<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Status;

class CityController extends Controller
{
    //metodo para mostrar datos en la pagina inicial
    public function index()
    {
        $ciudades = City::with('status')->active()->get();
        return view('ciudades.index_ciudades', compact('ciudades'));
    }

    //metodo para crear ciudades
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:cities|max:255'
        ]);

        City::create([
            'nombre' => $request->nombre,
            'status_id' => Status::ACTIVE
        ]);

        return redirect()->route('ciudades.index')
            ->with('success', 'Ciudad creada exitosamente');
    }

    // Método para desactivar una ciudad
    public function desactivate(City $ciudad)
    {
        if ($ciudad->desactivate()) {
            return redirect()->route('ciudades.index')
                ->with('success', 'Ciudad desactivada correctamente');
        }

        return redirect()->route('ciudades.index')
            ->with('error', 'No se pudo desactivar la ciudad');
    }
}
