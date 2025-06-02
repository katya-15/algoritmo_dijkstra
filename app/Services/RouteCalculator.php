<?php

namespace App\Services;

use App\Models\City;
use App\Models\Route;

class RouteCalculator
{
    public function calcularRutasOptimas($origenId, $destinoId)
    {
        $ciudades = City::with('status')->active()->get();
        $rutas = Route::with(['origenCity', 'destinoCity'])
            ->where('status_id', 1)->where('activo', 1) // Filtra solo status_id = 1
            ->get();

        // Construcción del grafo
        $grafo = [];
        foreach ($ciudades as $city) {
            $grafo[$city->id] = [];
        }

        foreach ($rutas as $route) {
            $grafo[$route->ciudad_origen_id][$route->ciudad_destino_id] = $route->distancia;
            $grafo[$route->ciudad_destino_id][$route->ciudad_origen_id] = $route->distancia;
        }

        // Dijkstra
        $rutaMasCorta = $this->dijkstra($grafo, $origenId, $destinoId);

        // Rutas alternativas
        $rutasAlternativas = $this->obtenerRutasAlternativas($grafo, $origenId, $destinoId, $rutaMasCorta['path']);

        return [
            'mas_corta' => $rutaMasCorta,
            'alternativas' => $rutasAlternativas
        ];
    }

    private function dijkstra($grafo, $origen, $destino)
    {
        $distancias = [];
        $previos = [];
        $nodos = new \SplPriorityQueue();

        foreach ($grafo as $vertice => $adyacentes) {
            $distancias[$vertice] = INF;
            $previos[$vertice] = null;
            $nodos->insert($vertice, INF);
        }

        $distancias[$origen] = 0;
        $nodos->insert($origen, 0);

        while (!$nodos->isEmpty()) {
            $actual = $nodos->extract();
            
            if ($actual === $destino) {
                break;
            }

            foreach ($grafo[$actual] as $vecino => $peso) {
                $alt = $distancias[$actual] + $peso;
                if ($alt < $distancias[$vecino]) {
                    $distancias[$vecino] = $alt;
                    $previos[$vecino] = $actual;
                    $nodos->insert($vecino, -$alt);
                }
            }
        }

        // Reconstruir el camino
        $path = [];
        $actual = $destino;
        while ($actual !== null) {
            $path[] = $actual;
            $actual = $previos[$actual];
        }

        $path = array_reverse($path);

        return [
            'distancia' => $distancias[$destino],
            'path' => $path,
        ];
    }

    private function obtenerRutasAlternativas($grafo, $origen, $destino, $rutaPrincipal)
    {
        $rutasAlternativas = [];
        
        for ($i = 0; $i < count($rutaPrincipal) - 1; $i++) {
            $grafoTemporal = $grafo;
            unset($grafoTemporal[$rutaPrincipal[$i]][$rutaPrincipal[$i+1]]);
            unset($grafoTemporal[$rutaPrincipal[$i+1]][$rutaPrincipal[$i]]);

            $resultado = $this->dijkstra($grafoTemporal, $origen, $destino);
            
            if ($resultado['distancia'] < INF) {
                $rutasAlternativas[] = $resultado;
            }

            if (count($rutasAlternativas) >= 2) {
                break;
            }
        }

        return $rutasAlternativas;
    }
}