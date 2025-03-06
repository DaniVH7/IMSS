<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Unidad;

use Illuminate\Http\Request;



//Consulta para buscar unidades por clues
class ClueController extends Controller
{
    public function buscarClues(Request $request)
{
    $query = $request->input('query');

    if (!$query) {
        return response()->json([]);
    }

    try {
        $unidades = DB::table('unidades as u')
            ->join('localidades as l', function ($join) {
                $join->on('l.idestado', '=', 'u.idestado')
                    ->on('l.idmunicipio', '=', 'u.idmunicipio')
                    ->on('l.idlocalidad', '=', 'u.idlocalidad');
            })
            ->join('municipios as m', function ($join) {
                $join->on('m.idestado', '=', 'l.idestado')
                    ->on('m.idmunicipio', '=', 'l.idmunicipio');
            })
            ->join('jurisdicciones as j', function ($join) {
                $join->on('j.idestado', '=', 'm.idestado')
                    ->on('j.idjurisdiccion', '=', 'm.idjurisdiccion');
            })
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('u.clues', 'LIKE', "%{$query}%")
                             ->orWhere('u.nombre', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->select(
                'u.clues',
                'u.nombre as unidad_medica',
                'u.latitud',
                'u.longitud',
                'u.idmunicipio',
                'm.municipio as nombre_municipio',
                'u.idlocalidad',
                'l.localidad as nombre_localidad',
                'm.idjurisdiccion',
                'j.jurisdiccion as nombre_jurisdiccion'
            )
            ->get();

        $resultados = $unidades->map(function ($unidad) {
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
        });

        return response()->json($resultados);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error en la consulta: ' . $e->getMessage()
        ], 500);
    }
}

// Consulta para buscar jurisdicción y unidades médicas asociadas a un municipio con coordenadas
public function buscarUnidadesPorMunicipio(Request $request)
{
    $nombreMunicipio = $request->input('municipio');

    if (!$nombreMunicipio) {
        return response()->json(['error' => 'Falta el nombre del municipio'], 400);
    }

    try {
        $unidades = DB::table('unidades as u')
            ->join('municipios as m', function ($join) {
                $join->on('m.idestado', '=', 'u.idestado')
                     ->on('m.idmunicipio', '=', 'u.idmunicipio');
            })
            ->where('m.municipio', 'LIKE', "%{$nombreMunicipio}%") 
            ->select(
                'u.clues',
                'u.nombre as unidad_medica',
                'm.municipio',
                'm.idmunicipio',
                'm.idjurisdiccion',
                'm.idestado',
                'u.latitud',   
                'u.longitud'   
            )
            ->get();

        if ($unidades->isEmpty()) {
            return response()->json(['message' => 'No se encontraron unidades en este municipio'], 404);
        }

        return response()->json($unidades);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}

public function buscarUnidades(Request $request)
{
    $query = DB::table('unidades_medicas');

    if ($request->has('clues')) {
        $query->where('clues', $request->clues);
    }
    if ($request->has('municipio')) {
        $query->where('municipio', 'LIKE', '%' . $request->municipio . '%');
    }
    if ($request->has('localidad')) {
        $query->where('localidad', 'LIKE', '%' . $request->localidad . '%');
    }
    if ($request->has('jurisdiccion')) {
        $query->where('jurisdiccion', $request->jurisdiccion);
    }

    $unidades = $query->get();
    
    return response()->json($unidades);
}

public function buscarMunicipiosConUnidades(Request $request) {
    $query = $request->input('query'); 

    $municipios = DB::table('municipios as m')
        ->leftJoin('unidades as u', 'm.idmunicipio', '=', 'u.idmunicipio')
        ->select('m.idmunicipio', 'm.municipio', 'm.idestado', 'm.idjurisdiccion')
        ->distinct()
        ->orderBy('m.municipio');

    if ($query) {
        $municipios->where('m.municipio', 'LIKE', "%{$query}%");
    }

    return response()->json($municipios->get());
}



public function buscarJurisdicciones(Request $request)
{
    try {
        $jurisdicciones = DB::table('jurisdicciones')
            ->select('idjurisdiccion', 'jurisdiccion')
            ->where('jurisdiccion', 'LIKE', "%" . $request->query('query') . "%")
            ->limit(10)
            ->get();

        return response()->json($jurisdicciones);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}
public function buscarUnidadesPorJurisdiccion(Request $request)
{
    $idjurisdiccion = $request->input('idjurisdiccion');

    if (!$idjurisdiccion) {
        return response()->json(['error' => 'Falta el parámetro idjurisdiccion'], 400);
    }

    $unidades = DB::table('unidades as u')
        ->join('municipios as m', 'u.idmunicipio', '=', 'm.idmunicipio')
        ->join('jurisdicciones as j', 'm.idjurisdiccion', '=', 'j.idjurisdiccion')
        ->where('j.idjurisdiccion', $idjurisdiccion)
        ->select(
            'u.clues',
            'u.nombre as unidad_medica',
            'm.municipio',
            'u.latitud',
            'u.longitud'
        )
        ->get();

    if ($unidades->isEmpty()) {
        return response()->json(['error' => 'No se encontraron unidades en esta jurisdicción'], 404);
    }

    return response()->json($unidades);
}

public function buscarLocalidadesConUnidades(Request $request)
{
    $query = $request->input('query');

    try {
        $localidades = DB::table('localidades as l')
            ->join('unidades as u', function ($join) {
                $join->on('l.idestado', '=', 'u.idestado')
                    ->on('l.idmunicipio', '=', 'u.idmunicipio')
                    ->on('l.idlocalidad', '=', 'u.idlocalidad');
            })
            ->select('l.idlocalidad', 'l.localidad')
            ->distinct()
            ->orderBy('l.localidad');

        if ($query) {
            $localidades->where('l.localidad', 'LIKE', "%{$query}%");
        }

        return response()->json($localidades->get());

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}

public function buscarUnidadesPorLocalidad(Request $request)
{
    $nombreLocalidad = $request->input('localidad');

    if (!$nombreLocalidad) {
        return response()->json(['error' => 'Falta el nombre de la localidad'], 400);
    }

    try {
        $unidades = DB::table('unidades as u')
            ->join('localidades as l', function ($join) {
                $join->on('l.idestado', '=', 'u.idestado')
                     ->on('l.idmunicipio', '=', 'u.idmunicipio')
                     ->on('l.idlocalidad', '=', 'u.idlocalidad');
            })
            ->where('l.localidad', 'LIKE', "%{$nombreLocalidad}%") 
            ->select(
                'u.clues',
                'u.nombre as unidad_medica',
                'l.localidad',
                'u.latitud',
                'u.longitud'
            )
            ->get();

        if ($unidades->isEmpty()) {
            return response()->json(['message' => 'No se encontraron unidades en esta localidad'], 404);
        }

        return response()->json($unidades);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}
public function buscarMunicipiosPorJurisdiccion(Request $request)
{
    $municipios = DB::table('municipios')
        ->where('idjurisdiccion', $request->idjurisdiccion)
        ->select('idmunicipio', 'municipio')
        ->get();

    return response()->json($municipios);
}
public function buscarLocalidadesPorMunicipio(Request $request)
{
    $nombreMunicipio = $request->input('municipio');

    if (!$nombreMunicipio) {
        return response()->json(['error' => 'Falta el nombre del municipio'], 400);
    }

    try {
        $localidades = DB::table('localidades as l')
            ->join('municipios as m', function ($join) {
                $join->on('m.idestado', '=', 'l.idestado')
                     ->on('m.idmunicipio', '=', 'l.idmunicipio');
            })
            ->where('m.municipio', 'LIKE', "%{$nombreMunicipio}%") // Búsqueda por nombre
            ->select('l.idlocalidad', 'l.localidad')
            ->distinct()
            ->orderBy('l.localidad')
            ->get();

        return response()->json($localidades);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}

public function buscarLocalidadesPorNombreMunicipio(Request $request)
{
    $nombreMunicipio = $request->input('municipio');

    if (!$nombreMunicipio) {
        return response()->json(['error' => 'Falta el nombre del municipio'], 400);
    }

    try {
        $localidades = DB::table('localidades as l')
            ->join('municipios as m', function ($join) {
                $join->on('m.idestado', '=', 'l.idestado')
                     ->on('m.idmunicipio', '=', 'l.idmunicipio');
            })
            ->where('m.municipio', 'LIKE', "%{$nombreMunicipio}%") // Búsqueda por nombre
            ->select('l.idlocalidad', 'l.localidad')
            ->distinct()
            ->orderBy('l.localidad')
            ->get();

        return response()->json($localidades);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}

public function buscarCluesPorLocalidad(Request $request)
{
    $nombreLocalidad = $request->input('localidad');

    if (!$nombreLocalidad) {
        return response()->json(['error' => 'Falta el nombre de la localidad'], 400);
    }

    try {
        $unidades = DB::table('unidades as u')
            ->join('localidades as l', function ($join) {
                $join->on('l.idestado', '=', 'u.idestado')
                     ->on('l.idmunicipio', '=', 'u.idmunicipio')
                     ->on('l.idlocalidad', '=', 'u.idlocalidad');
            })
            ->where('l.localidad', 'LIKE', "%{$nombreLocalidad}%") // Búsqueda por nombre
            ->select('u.clues', 'u.nombre as unidad_medica', 'l.localidad', 'u.latitud', 'u.longitud')
            ->get();

        if ($unidades->isEmpty()) {
            return response()->json(['message' => 'No se encontraron unidades en esta localidad'], 404);
        }

        return response()->json($unidades);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error en la consulta: ' . $e->getMessage()], 500);
    }
}

}
