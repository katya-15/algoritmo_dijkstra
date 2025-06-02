<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('admin.dashboard');

foreach (glob(__DIR__ . '/modulos/*.php') as $file){
    require $file;
}