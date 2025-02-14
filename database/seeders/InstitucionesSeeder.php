<?php

namespace Database\Seeders;

use App\Models\Institucion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class InstitucionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/instituciones.json');

        $instituciones = json_decode($json);

        //dd($instituciones);

        foreach($instituciones as $key => $value) {
            Institucion::create([
                'idinstitucion' => $value->idinstitucion,
                'institucion' => $value->institucion,
                'descripcion_corta' => $value->descripcion_corta,
                'iniciales' => $value->iniciales
            ]);
        }
    }
}
