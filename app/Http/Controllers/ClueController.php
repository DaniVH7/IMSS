<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Unidad;
use App\Models\Jurisdiccion;
use App\Models\Municipio;


//Consulta para buscar unidades por clues
class ClueController extends Controller
{
    public function buscarClues(Request $request)
{
    $query = $request->input('query');

    if (!$query) {
        return response()->json([]);
    }

    $unidades = Unidad::where('clues', 'LIKE', "%$query%")
        ->orWhere('nombre', 'LIKE', "%$query%")
        ->limit(10)
        ->get(['clues', 'nombre', 'latitud', 'longitud']);

    return response()->json($unidades->map(function ($unidad) {
        return [
            'id' => $unidad->clues,
            'nombre' => $unidad->nombre,
            'clues' => $unidad->clues,
            'latitud' => $unidad->latitud,
            'longitud' => $unidad->longitud
        ];
    }));
}

// Consulta para buscar jurisdicción y unidades médicas asociadas a un municipio con coordenadas
public function buscarUnidadesPorMunicipio(Request $request)
{
    $query = $request->input('query');

    if (!$query) {
        return response()->json([]);
    }

    $resultados = DB::table('municipios as m')
        ->join('jurisdicciones as j', 'm.idjurisdiccion', '=', 'j.idjurisdiccion')
        ->join('unidades as u', 'j.idjurisdiccion', '=', 'u.idjurisdiccion')
        ->where('m.municipio', 'LIKE', "%$query%")
        ->select(
            'm.idmunicipio', 
            'm.municipio', 
            'j.idjurisdiccion', 
            'j.jurisdiccion', 
            'u.clues', 
            'u.nombre as unidad_medica', 
            'u.latitud', 
            'u.longitud'
        )
        ->limit(10)
        ->get();

    return response()->json($resultados);
}

//consulta para buscar unidades por jurisdiccion
public function buscarUnidadesTulancingo(Request $request)
{
    try {
        $unidades = DB::table('unidades as u')
            ->join('municipios as m', 'u.idmunicipio', '=', 'm.idmunicipio')
            ->join('jurisdicciones as j', 'm.idjurisdiccion', '=', 'j.idjurisdiccion')
            ->where('j.jurisdiccion', '=', 'Jurisdicción Sanitaria II Tulancingo') // Filtrar solo Tulancingo
            ->select(
                'u.clues',
                'u.nombre as unidad_medica',
                'u.latitud',
                'u.longitud'
            )
            ->get();

        if ($unidades->isEmpty()) {
            return response()->json(['error' => 'No se encontraron unidades en esta jurisdicción'], 404);
        }

        return response()->json($unidades);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}





//consulta para buscar unidades por localidad
public function buscarUnidadesPorLocalidad(Request $request)
{
    $localidad = $request->input('localidad');

    if (!$localidad) {
        return response()->json([]);
    }

    $resultados = DB::table('unidades')
        ->where('nombre', 'LIKE', "%$localidad%") // Ajusta 'nombre' según tu tabla
        ->select('clues', 'nombre as unidad_medica', 'latitud', 'longitud')
        ->get();

    return response()->json($resultados);
}

}
