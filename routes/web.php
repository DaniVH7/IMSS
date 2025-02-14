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

    //Visualizar todos los estados
    //dd(App\Models\Estado::all());

        //Visualizar todos los municipios que empiecen con la letra H
        //dd(App\Models\Estado::where('estado', 'LIKE', '%H%')->get());
    
    // Visualizar todos las jurisdicciones
    //dd(App\Models\Jurisdiccion::all());

        // Visualizar los estados con sus jurisdicciones
        //dd(App\Models\Estado::with('jurisdicciones')->get());
        // Visualizar las jurisdicciones con sus estados
        //dd(App\Models\Jurisdiccion::with('estado')->get());

    // Visualizar todos los estratos
    //dd(App\Models\Estrato::all());

    // Visualizar todas las instituciones
    //dd(App\Models\Institucion::all());

    // Visaulizar todos los motivos de baja
    //dd(App\Models\MotivosBaja::all());
});
