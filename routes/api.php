<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;
use App\Http\Controllers\ClueController;
use App\Http\Controllers\TodoController;


Route::get('/unidades/buscarClues', [ClueController::class, 'buscarClues']);
Route::get('/unidades/buscarJurisdicciones', [ClueController::class, 'buscarJurisdicciones']);
Route::get('/unidades/buscarMunicipios', [ClueController::class, 'buscarMunicipios']);
Route::get('/unidades/buscarLocalidades', [ClueController::class, 'buscarLocalidades']);

    
Route::get('/unidades', [TodoController::class, 'index']);
Route::get('/api/unidades/buscarClues', [TodoController::class, 'buscarClues']);
Route::get('/api/unidades/obtenerDatosPorClues', [TodoController::class, 'obtenerDatosPorClues']);



Route::get('/unidades/buscarLocalidadesPorMunicipio', [ClueController::class, 'buscarLocalidadesPorMunicipio']);

Route::get('/unidades/buscarLocalidadesConUnidades', [ClueController::class, 'buscarLocalidadesConUnidades']);


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
use App\Http\Controllers\UnidadMedicaController;

Route::get('/unidades/obtenerUnidadPorClues', [UnidadMedicaController::class, 'obtenerUnidadPorClues']);
