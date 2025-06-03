<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Route;
use App\Services\RouteCalculator;

class RouteController extends Controller
{
    /**
     * Muestra todas las rutas activas junto a las ciudades activas
     */
    public function index()
    {
        $rutas = Route::with(['origenCity', 'destinoCity'])
            ->where('status_id', 1)
            ->get();

        $ciudades = City::with('status')->active()->get();

        return view('rutas.index_ruta', compact('rutas', 'ciudades'));
    }

    /**
     * Almacena una nueva ruta en la base de datos
     * Valida que no exista ya una ruta entre esas ciudades (ida o vuelta)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ciudad_origen_id' => 'required|exists:cities,id',
            'ciudad_destino_id' => 'required|exists:cities,id|different:ciudad_origen_id',
            'distancia' => 'required|numeric|min:1',
        ]);

        // Verifica si ya existe una ruta directa o inversa
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

        // Crea la nueva ruta
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

    /**
     * Activa o desactiva el estado de una ruta
     */
    public function updateStatus(Route $ruta)
    {
        $ruta->update(['activo' => !$ruta->activo]);

        return redirect()->route('rutas.index')
            ->with('success', 'Estado de ruta actualizado');
    }

    /**
     * Encuentra y muestra la mejor ruta entre dos ciudades activas
     */
    public function encontrarRuta(Request $request)
    {
        $request->validate([
            'origen' => 'required|exists:cities,id',
            'destino' => 'required|exists:cities,id|different:origen',
        ]);

        // Verifica que ambas ciudades estén activas
        $ciudadOrigen = City::active()->findOrFail($request->origen);
        $ciudadDestino = City::active()->findOrFail($request->destino);

        $ciudades = City::active()->get()->keyBy('id'); // Para acceso rápido por ID

        // Calcula rutas óptimas usando el servicio personalizado
        $calculador = new RouteCalculator();
        $resultado = $calculador->calcularRutasOptimas($request->origen, $request->destino);

        // Procesa las rutas para mostrar nombres de ciudades
        $procesarRuta = fn($ruta) => [
            'distancia' => $ruta['distancia'],
            'path' => array_map(fn($id) => $ciudades[$id]->nombre, $ruta['path'])
        ];

        $rutaCorta = $procesarRuta($resultado['mas_corta']);
        $rutasAlternativas = array_map($procesarRuta, $resultado['alternativas']);

        return view('rutas.resultado', [
            'origen' => $ciudadOrigen->nombre,
            'destino' => $ciudadDestino->nombre,
            'rutaCorta' => $rutaCorta,
            'rutasAlternativas' => $rutasAlternativas,
            'ciudades' => $ciudades
        ]);
    }

    /**
     * Desactiva una ruta usando un método en el modelo
     */
    public function desactivate(Route $ruta)
    {
        if ($ruta->desactivate()) {
            return redirect()->route('rutas.index')
                ->with('success', 'Ruta desactivada correctamente');
        }

        return redirect()->route('rutas.index')
            ->with('error', 'No se pudo desactivar la ruta');
    }

    /**
     * Actualiza los datos de una ruta
     * También valida que la nueva combinación origen-destino no exista ya
     */
    public function update(Request $request, Route $ruta)
    {
        $request->validate([
            'ciudad_origen_id' => 'required|exists:cities,id',
            'ciudad_destino_id' => 'required|exists:cities,id|different:ciudad_origen_id',
            'distancia' => 'required|numeric|min:1',
        ]);

        $origenCambiado = $ruta->ciudad_origen_id != $request->ciudad_origen_id;
        $destinoCambiado = $ruta->ciudad_destino_id != $request->ciudad_destino_id;

        // Solo se verifica duplicidad si se cambiaron las ciudades
        if ($origenCambiado || $destinoCambiado) {
            $rutaExistente = Route::where('id', '!=', $ruta->id)
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

        // Actualiza la ruta
        $ruta->update([
            'ciudad_origen_id' => $request->ciudad_origen_id,
            'ciudad_destino_id' => $request->ciudad_destino_id,
            'distancia' => $request->distancia,
        ]);

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta actualizada exitosamente');
    }
}
