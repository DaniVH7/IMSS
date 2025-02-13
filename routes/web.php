<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;


Route::get('/', function () {
    return view('welcome');
});

// Obtener un JSON completo
Route::get('/json/{archivo}', [JsonController::class, 'obtenerJson']);

// Buscar municipios por estado
Route::get('/municipios/{idestado}', [JsonController::class, 'buscarMunicipiosPorEstado']);

// Buscar localidades por municipio
Route::get('/localidades/{idmunicipio}', [JsonController::class, 'buscarLocalidadesPorMunicipio']);

// Buscar unidades por municipio
Route::get('/unidades/{idmunicipio}', [JsonController::class, 'buscarUnidadesPorMunicipio']);


Route::get('/ver-json', function() {
    return view('json_view');
});

Route::get('tulancingo', function() {
    return view('tulancingo');
});

Route::get('pruebas', function(){
    //dd(App\Models\Estado::all());
    //dd(App\Models\Estado::where('estado', 'LIKE', '%H%')->get());
    //dd(App\Models\Jurisdiccion::all());

    //dd(App\Models\Estado::with('jurisdicciones')->get());
    dd(App\Models\Jurisdiccion::with('estado')->get());
});
