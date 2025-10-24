<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidatosController;
use App\Http\Middleware\NormalizeInput;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/', function () {
    return view('welcome');
});

Route::get('/candidatos', [CandidatosController::class, 'index']);
Route::get('/candidatos/{id}', [CandidatosController::class, 'show']);
Route::post('/candidatos/store', [CandidatosController::class, 'store'])->middleware(NormalizeInput::class);
Route::put('/candidatos/{id}', [CandidatosController::class, 'update'])->middleware(NormalizeInput::class);
Route::delete('/candidatos/{id}', [CandidatosController::class, 'destroy']);
Route::post('/candidatos/import', [CandidatosController::class, 'import'])->middleware(NormalizeInput::class);
    
Route::get('/form', function () {
    return view('candidatos');
});
