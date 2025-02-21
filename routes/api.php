<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;
use App\Http\Controllers\ClueController;

Route::get('/unidades/buscarClues', [ClueController::class, 'buscarClues']);
Route::get('/unidades/buscarJurisdicciones', [ClueController::class, 'buscarJurisdicciones']);
Route::get('/unidades/buscarMunicipios', [ClueController::class, 'buscarMunicipios']);
Route::get('/unidades/buscarLocalidades', [ClueController::class, 'buscarLocalidades']);


Route::get('/', function () {
    return view('welcome');
});

// Obtener un JSON completo
Route::get('/json/{archivo}', [JsonController::class, 'obtenerJson']);

// Buscar municipios por estado
Route::get('/municipios/{idEstado}', [JsonController::class, 'buscarMunicipiosPorEstado']);

// Buscar localidades por municipio
Route::get('/localidades/{idMunicipio}', [JsonController::class, 'buscarLocalidadesPorMunicipio']);

// Buscar unidades por municipio
Route::get('/unidades/{idMunicipio}', [JsonController::class, 'buscarUnidadesPorMunicipio']);
