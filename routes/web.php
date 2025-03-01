<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;
use App\Http\Controllers\ClueController;

Route::get('/api/unidades/buscar', [ClueController::class, 'buscar']);
Route::get('/buscar-unidades', function () {
    return view('buscar-unidades');
});

Route::get('/api/unidades/buscar', [ClueController::class, 'buscar']);
Route::get('/buscar-unidades', function () {
    return view('buscar-unidades');
});
// Ruta para la búsqueda por CLUES
Route::get('/api/unidades/buscarClues', [ClueController::class, 'buscarClues']);

// Ruta para la búsqueda por Municipio
Route::get('/api/unidades/buscarUnidadesPorMunicipio', [ClueController::class, 'buscarUnidadesPorMunicipio']);

// Ruta para la búsqueda por Jurisdicción Sanitaria II Tulancingo
Route::get('/api/unidades/buscarUnidadesTulancingo', [ClueController::class, 'buscarUnidadesTulancingo']);

Route::get('/api/unidades/buscarUnidadesTulancingo', [ClueController::class, 'buscarUnidadesTulancingo']);
Route::get('/api/unidades/buscarUnidadesPorLocalidad', [ClueController::class, 'buscarUnidadesPorLocalidad']);
Route::get('/api/unidades/buscarUnidadesPorLocalidad', [ClueController::class, 'buscarUnidadesPorLocalidad']);
Route::get('/api/unidades/buscarUnidadesPorJurisdiccion', [ClueController::class, 'buscarUnidadesPorJurisdiccion']);

Route::get('/api/unidades/buscarUnidadesTulancingo', [ClueController::class, 'buscarUnidadesTulancingo']);
Route::get('/api/unidades/buscarUnidadesPorLocalidad', [ClueController::class, 'buscarUnidadesPorLocalidad']);
Route::get('/api/unidades/buscarUnidadesPorLocalidad', [ClueController::class, 'buscarUnidadesPorLocalidad']);
Route::get('/api/unidades/buscarUnidadesPorLocalidad', [ClueController::class, 'buscarUnidadesPorLocalidad']);
Route::get('/api/unidades/buscarUnidadesPorLocalidad', [ClueController::class, 'buscarUnidadesPorLocalidad']);




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


Route::get('/ver-json', function () {
    return view('json_view');
});

Route::get('tulancingo', function () {
    return view('tulancingo');
});

Route::get('pruebas', function () {

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

    // Visualizar todos los niveles de atención
    //dd(App\Models\NivelAtencion::all());

    // Visualizar todos los status de unidades
    //dd(App\Models\StatusUnidad::all());

    // Visualizar todas las subtipologias
    //dd(App\Models\Subtipologia::all());

    // Visualizar todas las tipologias de unidades
    //dd(App\Models\TipologiaUnidad::all());

    // Visualizar todos los tipos de administración
    //dd(App\Models\TipoAdministracion::all());

    // Visualizar todos los tipos de asentamientos
    //dd(App\Models\TipoAsentamiento::all());

    // Visualizar todos los tipos de establecimiento
    //dd(App\Models\TipoEstablecimiento::all());

    // Visualizar todos los tipos de unidades
    //dd(App\Models\TipoUnidad::all());

    // Visualizar todos los tipos de vialidades
    //dd(App\Models\TipoVialidad::all());

    // Visualizar todos los municipios
    //dd(App\Models\Municipio::all());

    // Visualizar todos las localidades
    // dd(App\Models\Localidad::all());

    // Visualizar todos las Unidades
    // dd(App\Models\Unidad::all());

    // Visualizar todos las Unidades
    dd(App\Models\UnidadHospital::all());
});



Route::get('/mapa2', function () {
    return view('mapa2');
});

use App\Http\Controllers\UnidadMedicaController;

Route::get('/unidades/obtenerUnidadPorClues', [UnidadMedicaController::class, 'obtenerUnidadPorClues']);
