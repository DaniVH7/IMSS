<?php

namespace Database\Seeders;

use App\Models\Unidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class UnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/unidades.json');

        $unidades = json_decode($json);

        foreach($unidades as $key => $value) {
            Unidad::create([
                'idestado' => $value->idestado,
                'idmunicipio' => $value->idmunicipio,
                'idlocalidad' => $value->idlocalidad,
                'clues' => $value->clues,
                'idtipo_unidad' => $value->idtipo_unidad,
                'idtipologia_unidad' => $value->idtipologia_unidad,
                'idinstitucion' => $value->idinstitucion,
                'idestrato' => $value->idestrato,
                'idjurisdiccion' => $value->idjurisdiccion,
                'idtipo_vialidad' => $value->idtipo_vialidad,
                'idtipo_asentamiento' => $value->idtipo_asentamiento,
                'idtipo_administracion' => $value->idtipo_administracion,
                'idstatus_unidad' => $value->idstatus_unidad,
                'idmotivo_baja' => $value->idmotivo_baja,
                'idtipo_establecimiento' => $value->idtipo_establecimiento,
                'idsubtipologia' => $value->idsubtipologia,
                'idnivel_atencion' => $value->idnivel_atencion,
                'nombre' => $value->nombre,
                'vialidad' => $value->vialidad,
                'asentamiento' => $value->asentamiento,
                'nointerior' => $value->nointerior,
                'noexterior' => $value->noexterior,
                'cp' => $value->cp,
                'latitud' => $value->latitud,
                'longitud' => $value->longitud,
                'email' => $value->email,
                'telefono' => $value->telefono,
                'inicio_operacion' => $value->inicio_operacion,
                'horarios' => $value->horarios,
                'nombre_responsable' => $value->nombre_responsable,
                'pa_responsable' => $value->pa_responsable,
                'sa_responsable' => $value->sa_responsable,
                'cedula_responsable' => $value->cedula_responsable
            ]);
        }
    }
}
