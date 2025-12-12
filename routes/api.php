<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\ListasController;
use App\Http\Controllers\MesasController;
use App\Http\Controllers\TelegramasController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\ProvinciasController;
use App\Http\Controllers\CalculoDeBancasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Rutas para Candidatos
Route::get('/candidatos/total/{id}', [CandidatosController::class, 'total']);

Route::get('/candidatos', [CandidatosController::class, 'index']);
Route::get('/candidatos/{id}', [CandidatosController::class, 'show']);
Route::post('/candidatos', [CandidatosController::class, 'store']);
Route::put('/candidatos/{id}', [CandidatosController::class, 'update']);
Route::delete('/candidatos/{id}', [CandidatosController::class, 'destroy']);

// Rutas para Listas
Route::get('/listas/resultados', [ListasController::class, 'resultados']);

// Rutas para Provincias (ABM)
Route::get('/provincias', [ProvinciasController::class, 'index']);
Route::get('/provincias/{id}', [ProvinciasController::class, 'show']);
Route::post('/provincias', [ProvinciasController::class, 'store']);
Route::put('/provincias/{id}', [ProvinciasController::class, 'update']);
Route::delete('/provincias/{id}', [ProvinciasController::class, 'destroy']);

Route::get('/listas', [ListasController::class, 'index']);
Route::get('/listas/{id}', [ListasController::class, 'show']);
Route::post('/listas', [ListasController::class, 'store']);
Route::put('/listas/{id}', [ListasController::class, 'update']);
Route::delete('/listas/{id}', [ListasController::class, 'destroy']);

// Rutas para Mesas
Route::get('/mesas', [MesasController::class, 'index']);
Route::get('/mesas/{id}', [MesasController::class, 'show']);
Route::post('/mesas', [MesasController::class, 'store']);
Route::put('/mesas/{id}', [MesasController::class, 'update']);
Route::delete('/mesas/{id}', [MesasController::class, 'destroy']);

// Rutas para Telegramas
Route::get('/telegramas', [TelegramasController::class, 'index']);
Route::get('/telegramas/{id}', [TelegramasController::class, 'show']);
Route::post('/telegramas', [TelegramasController::class, 'store']);
Route::put('/telegramas/{id}', [TelegramasController::class, 'update']);
Route::delete('/telegramas/{id}', [TelegramasController::class, 'destroy']);
    

Route::post('/import', [ImportController::class, 'import']);

Route::get('/resultados/ranking', [ResultadosController::class, 'rankingListas']);
Route::get('/resultados/participacion', [ResultadosController::class, 'participacionNacional']);
Route::get('/resultados/verCandidato/{id}', [ResultadosController::class, 'verCandidato']);
Route::get('/resultados/cantidades', [ResultadosController::class, 'cantidades']);

Route::get('/calculoBancas', [CalculoDeBancasController::class, 'calcular']);
Route::get('/legisladores', [CalculoDeBancasController::class, 'asignarBancas']);

Route::get('/form', function () {
    return view('candidatos');
});
