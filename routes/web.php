<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidatosController;
use App\Http\Middleware\NormalizeInput;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/candidatos/create', [CandidatosController::class, 'create'])->middleware(NormalizeInput::class);

Route::post('/candidatos/import', [CandidatosController::class, 'import'])->middleware(NormalizeInput::class);
    
Route::get('/form', function () {
    return view('candidatos');
});
