<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;

// Rutas para Ciudades
Route::prefix('ciudades')->group(function () {
    Route::get('/', [CityController::class, 'index'])->name('ciudades.index');
    Route::post('/', [CityController::class, 'store'])->name('ciudades.store');
    // En routes/web.php
    Route::post('/ciudades/{ciudad}/desactivate', [CityController::class, 'desactivate'])
        ->name('ciudades.desactivate');
});
