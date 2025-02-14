<?php

namespace Database\Seeders;


use App\Models\TipoAdministracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class TiposAdministracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/tipos_administracion.json');

        $tipos_administracion = json_decode($json);

        //dd($tipos_administracion);

        foreach($tipos_administracion as $key => $value) {
            TipoAdministracion::create([
                'idtipo_administracion' => $value->idtipo_administracion,
                'tipo_administracion' => $value->tipo_administracion
            ]);
        }
    }
}
