<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitsSearchController extends Controller
{
    public function buscarClues(Request $request)
    {
        $query = $request->input('query');
    
        if (!$query) {
            return response()->json([]);
        }
    
        $unidades = DB::table('unidades as u')
            ->join('localidades as l', 'u.idlocalidad', '=', 'l.idlocalidad')
            ->join('municipios as m', 'l.idmunicipio', '=', 'm.idmunicipio')
            ->join('jurisdicciones as j', 'm.idjurisdiccion', '=', 'j.idjurisdiccion')
            ->where(function ($q) use ($query) {
                $q->where('u.clues', 'LIKE', "%$query%")
                  ->orWhere('u.nombre', 'LIKE', "%$query%");
            })
            ->limit(10)
            
            ->select(
                'u.clues',
                'u.nombre as unidad_medica',
                'u.latitud',
                'u.longitud',
                'u.idlocalidad',
                'l.localidad as nombre_localidad',
                'l.idmunicipio',
                'm.municipio as nombre_municipio',
                'm.idjurisdiccion',
                'j.jurisdiccion as nombre_jurisdiccion'
            )
            ->get();

        return response()->json($unidades->map(function ($unidad) {
            return [
                'id' => $unidad->clues,
                'nombre' => $unidad->unidad_medica,
                'clues' => $unidad->clues,
                'latitud' => $unidad->latitud,
                'longitud' => $unidad->longitud,
                'idmunicipio' => $unidad->idmunicipio,
                'municipio' => $unidad->nombre_municipio,
                'idlocalidad' => $unidad->idlocalidad,
                'localidad' => $unidad->nombre_localidad,
                'idjurisdiccion' => $unidad->idjurisdiccion,
                'jurisdiccion' => $unidad->nombre_jurisdiccion
            ];
        }));
    }
}
