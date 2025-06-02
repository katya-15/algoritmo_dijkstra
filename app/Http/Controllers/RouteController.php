<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Route;
use App\Services\RouteCalculator;

class RouteController extends Controller
{
    public function index()
    {
        $rutas = Route::with(['origenCity', 'destinoCity'])
            ->where('status_id', 1)
            ->get();
        $ciudades = City::with('status')->active()->get();

        return view('rutas.index_ruta', compact('rutas', 'ciudades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ciudad_origen_id' => 'required|exists:cities,id',
            'ciudad_destino_id' => 'required|exists:cities,id|different:ciudad_origen_id',
            'distancia' => 'required|integer|min:1',
            'activo' => 'sometimes'
        ]);

        // Verificar ruta existente
        $rutaExistente = Route::where(function ($q) use ($request) {
            $q->where('ciudad_origen_id', $request->ciudad_origen_id)
                ->where('ciudad_destino_id', $request->ciudad_destino_id);
        })->orWhere(function ($q) use ($request) {
            $q->where('ciudad_origen_id', $request->ciudad_destino_id)
                ->where('ciudad_destino_id', $request->ciudad_origen_id);
        })->exists();

        if ($rutaExistente) {
            return back()->with('error', 'Ya existe una ruta entre estas ciudades');
        }

        Route::create([
            'ciudad_origen_id' => $request->ciudad_origen_id,
            'ciudad_destino_id' => $request->ciudad_destino_id,
            'distancia' => $request->distancia,
            'activo' => $request->has('activo'),
            'status_id' => 1
        ]);

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta creada exitosamente');
    }

    public function updateStatus(Route $ruta)
    {
        $ruta->update(['activo' => !$ruta->activo]);

        return redirect()->route('rutas.index')
            ->with('success', 'Estado de ruta actualizado');
    }

    public function encontrarRuta(Request $request)
    {
        $request->validate([
            'origen' => 'required|exists:cities,id',
            'destino' => 'required|exists:cities,id|different:origen',
        ]);

        // Verificar que las ciudades de origen y destino estén ACTIVAS (status_id = 1)
        $ciudadOrigen = City::active()->findOrFail($request->origen);
        $ciudadDestino = City::active()->findOrFail($request->destino);

        // Obtener solo ciudades activas para evitar mostrar nombres de ciudades inactivas
        $ciudades = City::active()->get()->keyBy('id');

        // Instanciar el calculador de rutas (asumiendo que internamente filtra rutas activas)
        $calculador = new RouteCalculator();
        $resultado = $calculador->calcularRutasOptimas($request->origen, $request->destino);

        // Procesar los caminos para incluir nombres de ciudades (solo activas)
        $procesarRuta = function ($ruta) use ($ciudades) {
            return [
                'distancia' => $ruta['distancia'],
                'path' => array_map(function ($ciudadId) use ($ciudades) {
                    return $ciudades[$ciudadId]->nombre; // Solo ciudades activas
                }, $ruta['path'])
            ];
        };

        $rutaCorta = $procesarRuta($resultado['mas_corta']);
        $rutasAlternativas = array_map($procesarRuta, $resultado['alternativas']);

        return view('rutas.resultado', [
            'origen' => $ciudadOrigen->nombre,
            'destino' => $ciudadDestino->nombre,
            'rutaCorta' => $rutaCorta,
            'rutasAlternativas' => $rutasAlternativas,
            'ciudades' => $ciudades // Solo ciudades activas para dropdowns/filtros
        ]);
    }


    public function desactivate(Route $ruta)
    {
        if ($ruta->desactivate()) {
            return redirect()->route('rutas.index')
                ->with('success', 'Ciudad desactivada correctamente');
        }

        return redirect()->route('rutas.index')
            ->with('error', 'No se pudo desactivar la ciudad');
    }

    public function update(Request $request, Route $ruta)
    {
        $validated = $request->validate([
            'ciudad_origen_id' => 'required|exists:cities,id',
            'ciudad_destino_id' => 'required|exists:cities,id|different:ciudad_origen_id',
            'distancia' => 'required|integer|min:1',
        ]);

        // Verificar si se están modificando las ciudades
        $origenCambiado = $ruta->ciudad_origen_id != $request->ciudad_origen_id;
        $destinoCambiado = $ruta->ciudad_destino_id != $request->ciudad_destino_id;

        // Solo verificar rutas existentes si se modificaron las ciudades
        if ($origenCambiado || $destinoCambiado) {
            $rutaExistente = Route::where('id', '!=', $ruta->id) // Excluir la ruta actual
                ->where(function ($q) use ($request) {
                    $q->where('ciudad_origen_id', $request->ciudad_origen_id)
                        ->where('ciudad_destino_id', $request->ciudad_destino_id);
                })->orWhere(function ($q) use ($request) {
                    $q->where('ciudad_origen_id', $request->ciudad_destino_id)
                        ->where('ciudad_destino_id', $request->ciudad_origen_id);
                })->exists();

            if ($rutaExistente) {
                return back()->with('error', 'Ya existe una ruta entre estas ciudades');
            }
        }

        $ruta->update([
            'ciudad_origen_id' => $request->ciudad_origen_id,
            'ciudad_destino_id' => $request->ciudad_destino_id,
            'distancia' => $request->distancia,
        ]);

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta actualizada exitosamente');
    }
}
