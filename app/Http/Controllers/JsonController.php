<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

$contenido = Storage::get("json/localidades.json");



class JsonController extends Controller
{
    /**
     * Leer un archivo JSON y devolver su contenido.
     */
    public function obtenerJson($archivo)
    {
        $ruta = public_path("json/{$archivo}.json");

        if (!file_exists($ruta)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        $contenido = file_get_contents($ruta);
        $datos = json_decode($contenido, true); // Convertir a array asociativo

        return response()->json($datos);
    }

    /**
     * Filtrar municipios por estado.
     */
    public function buscarMunicipiosPorEstado($idEstado)
    {
        $ruta = storage_path("app/json/municipios.json");

        if (!file_exists($ruta)) {
            return response()->json(['error' => 'Archivo de municipios no encontrado'], 404);
        }

        $contenido = file_get_contents($ruta);
        $municipios = json_decode($contenido, true);

        $filtrados = array_filter($municipios, function ($municipio) use ($idEstado) {
            return $municipio['idestado'] == $idEstado;
        });

        return response()->json(array_values($filtrados));
    }

    /**
     * Buscar localidades por municipio usando Storage
     */
    public function buscarLocalidadesPorMunicipio($idmunicipio)
    {
        $ruta = storage_path("app/json/localidades.json");
    
        if (!file_exists($ruta)) {
            return response()->json(['error' => 'Archivo de localidades no encontrado'], 404);
        }
    
        $contenido = file_get_contents($ruta);
        $localidades = json_decode($contenido, true);
    
        // Verifica si hay datos en el JSON
        if (empty($localidades)) {
            return response()->json(['error' => 'El archivo JSON está vacío o tiene un formato incorrecto'], 500);
        }
    
        // ✅ Aplica el filtro correctamente
        $filtradas = array_filter($localidades, function ($localidad) use ($idmunicipio) {
            return isset($localidad['idmunicipio']) && $localidad['idmunicipio'] == $idmunicipio;
        });
    
        return response()->json(array_values($filtradas));
    }
    


    /**
     * Buscar unidades médicas por municipio.
     */
    public function buscarUnidadesPorMunicipio($idmunicipio)
    {
        $ruta = storage_path("app/json/unidades.json");

        if (!file_exists($ruta)) {
            return response()->json(['error' => 'Archivo de unidades no encontrado'], 404);
        }

        $contenido = file_get_contents($ruta);
        $unidades = json_decode($contenido, true);

        return response()->json($unidades); // Devuelve todo el JSON sin filtrar
    }
}
