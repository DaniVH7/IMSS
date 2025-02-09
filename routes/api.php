<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;


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
