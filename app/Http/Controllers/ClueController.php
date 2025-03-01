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
    
        $unidades = DB::table('unidades as u')
            ->join('municipios as m', 'u.idmunicipio', '=', 'm.idmunicipio')
            ->join('localidades as l', 'u.idlocalidad', '=', 'l.idlocalidad') 
            ->where('u.clues', 'LIKE', "%$query%")
            ->orWhere('u.nombre', 'LIKE', "%$query%")
            ->limit(10)
            ->select(
                'u.clues',
                'u.nombre as unidad_medica',
                'u.latitud',
                'u.longitud',
                'u.idmunicipio',  // ✅ Incluir ID del municipio
                'm.municipio as nombre_municipio',
                'u.idlocalidad',  // ✅ Incluir ID de la localidad
                'l.localidad as nombre_localidad'
            )
            ->get();
    
        return response()->json($unidades->map(function ($unidad) {
            return [
                'id' => $unidad->clues,
                'nombre' => $unidad->unidad_medica,
                'clues' => $unidad->clues,
                'latitud' => $unidad->latitud,
                'longitud' => $unidad->longitud,
                'idmunicipio' => $unidad->idmunicipio,  // ✅ Ahora el JSON incluye ID del municipio
                'municipio' => $unidad->nombre_municipio,
                'idlocalidad' => $unidad->idlocalidad,  // ✅ Ahora el JSON incluye ID de la localidad
                'localidad' => $unidad->nombre_localidad
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
