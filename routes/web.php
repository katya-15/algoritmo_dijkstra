<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboarController;

Route::get('/', [DashboarController::class, 'index'])->name('admin.dashboard');

// Route::get('/', function () {
//     return view('welcome');
// })->name('admin.dashboard');

foreach (glob(__DIR__ . '/modulos/*.php') as $file){
    require $file;
}