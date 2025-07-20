<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/',[MainController::class,'home'])->name('home');
Route::post('/gerar-exercicios',[MainController::class,'gerarExercicios'])->name('gerarExercicios');
Route::get('/imprimir-exercicios',[MainController::class,'imprimirExercicios'])->name('imprimirExercicios');
Route::get('/exportar-exercicios',[MainController::class,'exportarExercicios'])->name('exportarExercicios');



