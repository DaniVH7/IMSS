<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidad;

class UnidadTipoController extends Controller
{
    /**
     * Buscar el tipo de unidades or CLUES o nombre.
     */
    public function buscar(Request $request)
{
    try {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'No se recibió consulta'], 400);
        }

        $unidades = Unidad::where('clues', 'LIKE', "%$query%")
            ->orWhere('nombre', 'LIKE', "%$query%")
            ->limit(10)
            ->get(['clues', 'nombre']);

        if ($unidades->isEmpty()) {
            return response()->json(['error' => 'No se encontraron resultados'], 404);
        }

        return response()->json($unidades);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}

?>