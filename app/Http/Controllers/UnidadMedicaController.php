<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidad;
use App\Models\Jurisdiccion;
use App\Models\Municipio;

class UnidadMedicaController extends Controller
{
    public function obtenerUnidadPorClues(Request $request)
    {
        $clues = $request->query('clues');

        // Verificar si la consulta llega correctamente
        if (!$clues) {
            return response()->json(['error' => 'CLUES no recibido'], 400);
        }

        // Buscar la unidad médica con el CLUES recibido
        $unidad = Unidad::where('clues', $clues)->orWhere('nombre', $clues)->first();

        // Si no encuentra resultados
        if (!$unidad) {
            return response()->json(['error' => 'CLUES no encontrado'], 404);
        }

        // Buscar la jurisdicción y el municipio relacionados
        $jurisdiccion = Jurisdiccion::find($unidad->idjurisdiccion);
        $municipio = Municipio::find($unidad->idmunicipio);

        return response()->json([
            'idjurisdiccion' => $unidad->idjurisdiccion,
            'jurisdiccion' => $jurisdiccion ? $jurisdiccion->nombre : 'No disponible',
            'idmunicipio' => $unidad->idmunicipio,
            'municipio' => $municipio ? $municipio->nombre : 'No disponible',
            'nombre' => $unidad->nombre,
            'clues' => $unidad->clues,
            'latitud' => $unidad->latitud,
            'longitud' => $unidad->longitud
        ]);
    }
}
