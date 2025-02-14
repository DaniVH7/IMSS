<?php

namespace Database\Seeders;

use App\Models\TipoUnidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class TiposUnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/tipos_unidades.json');

        $tipos_unidades = json_decode($json);

        //dd($tipos_unidades);

        foreach($tipos_unidades as $key => $value) {
            TipoUnidad::create([
                'idtipo_unidad' => $value->idtipo_unidad,
                'tipo_unidad' => $value->tipo_unidad
            ]);
        }
    }
}
