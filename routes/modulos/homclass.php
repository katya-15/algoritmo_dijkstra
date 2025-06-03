<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomclassController;

Route::prefix('homclass')->group(function () {
    Route::get('/', [HomclassController::class, 'index'])->name('homclass.index');
});