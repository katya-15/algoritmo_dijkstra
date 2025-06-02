<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;

Route::prefix('rutas')->group(function () {
    Route::get('/', [RouteController::class, 'index'])->name('rutas.index');
    Route::post('/', [RouteController::class, 'store'])->name('rutas.store');
    Route::patch('/{ruta}/status', [RouteController::class, 'updateStatus'])->name('rutas.updateStatus');
    Route::post('/{ruta}/desactivate', [RouteController::class, 'desactivate'])->name('rutas.desactivate');
    
    // Ruta para actualizar una ruta específica
    Route::put('/{ruta}', [RouteController::class, 'update'])->name('rutas.update');
    
    // Cambiado a GET para permitir acceso directo y POST para formularios
    Route::match(['get', 'post'], '/encontrar', [RouteController::class, 'encontrarRuta'])->name('rutas.encontrar');
});