<?php

namespace App\Services;

use App\Models\City;
use App\Models\Route;

class RouteCalculator
{
    /**
     * Calcula la ruta más corta y rutas alternativas entre dos ciudades activas
     */
    public function calcularRutasOptimas($origenId, $destinoId)
    {
        // Obtener todas las ciudades activas y rutas activas (status_id = 1)
        $ciudades = City::with('status')->active()->get();
        $rutas = Route::with(['origenCity', 'destinoCity'])
            ->where('status_id', 1)
            ->where('activo', 1)
            ->get();

        // Crear grafo basado en las rutas activas
        $grafo = $this->construirGrafo($ciudades, $rutas);

        // Calcular la ruta más corta con Dijkstra
        $rutaMasCorta = $this->dijkstra($grafo, $origenId, $destinoId);

        // Obtener hasta 2 rutas alternativas excluyendo partes de la ruta principal
        $rutasAlternativas = $this->obtenerRutasAlternativas($grafo, $origenId, $destinoId, $rutaMasCorta['path']);

        return [
            'mas_corta' => $rutaMasCorta,
            'alternativas' => $rutasAlternativas
        ];
    }

    /**
     * Construye un grafo no dirigido con las distancias entre ciudades
     */
    private function construirGrafo($ciudades, $rutas)
    {
        $grafo = [];

        // Inicializa los nodos del grafo
        foreach ($ciudades as $city) {
            $grafo[$city->id] = [];
        }

        // Agrega aristas en ambos sentidos (grafo no dirigido)
        foreach ($rutas as $route) {
            $grafo[$route->ciudad_origen_id][$route->ciudad_destino_id] = $route->distancia;
            $grafo[$route->ciudad_destino_id][$route->ciudad_origen_id] = $route->distancia;
        }

        return $grafo;
    }

    /**
     * Implementa el algoritmo de Dijkstra para encontrar la ruta más corta
     */
    private function dijkstra($grafo, $origen, $destino)
    {
        $distancias = [];
        $previos = [];
        $nodos = new \SplPriorityQueue();

        // Inicializa distancias y prioridad
        foreach ($grafo as $vertice => $adyacentes) {
            $distancias[$vertice] = INF;
            $previos[$vertice] = null;
            $nodos->insert($vertice, INF);
        }

        // Distancia al nodo origen es 0
        $distancias[$origen] = 0;
        $nodos->insert($origen, 0);

        // Iterar mientras haya nodos en la cola de prioridad
        while (!$nodos->isEmpty()) {
            $actual = $nodos->extract();

            // Si llegamos al destino, terminamos
            if ($actual === $destino) break;

            // Evaluar vecinos del nodo actual
            foreach ($grafo[$actual] as $vecino => $peso) {
                $alt = $distancias[$actual] + $peso;

                if ($alt < $distancias[$vecino]) {
                    $distancias[$vecino] = $alt;
                    $previos[$vecino] = $actual;
                    $nodos->insert($vecino, -$alt); // Negativo para prioridad correcta
                }
            }
        }

        // Reconstruir el camino desde el destino al origen
        $path = [];
        $actual = $destino;
        while ($actual !== null) {
            $path[] = $actual;
            $actual = $previos[$actual];
        }

        return [
            'distancia' => $distancias[$destino],
            'path' => array_reverse($path), // Ruta en orden desde origen hasta destino
        ];
    }

    /**
     * Genera rutas alternativas eliminando una arista a la vez de la ruta principal
     */
    private function obtenerRutasAlternativas($grafo, $origen, $destino, $rutaPrincipal)
    {
        $rutasAlternativas = [];

        // Por cada par de ciudades consecutivas en la ruta principal
        for ($i = 0; $i < count($rutaPrincipal) - 1; $i++) {
            $grafoTemporal = $grafo;

            // Elimina temporalmente la conexión (ida y vuelta)
            unset($grafoTemporal[$rutaPrincipal[$i]][$rutaPrincipal[$i + 1]]);
            unset($grafoTemporal[$rutaPrincipal[$i + 1]][$rutaPrincipal[$i]]);

            // Recalcular ruta sin esa conexión
            $resultado = $this->dijkstra($grafoTemporal, $origen, $destino);

            // Si existe una ruta válida, la agregamos
            if ($resultado['distancia'] < INF) {
                $rutasAlternativas[] = $resultado;
            }

            // Limitar a máximo 2 rutas alternativas
            if (count($rutasAlternativas) >= 2) {
                break;
            }
        }

        return $rutasAlternativas;
    }
}
