<?php

namespace Database\Seeders;

use App\Models\TipologiaUnidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class TipologiasUnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/tipologias_unidades.json');

        $tipologias_unidades = json_decode($json);

        //dd($tipologias_unidades);

        foreach($tipologias_unidades as $key => $value) {
            TipologiaUnidad::create([
                'idtipologia_unidad' => $value->idtipologia_unidad,
                'tipologia_unidad' => $value->tipologia_unidad,
                'clave_tipologia' => $value->clave_tipologia
            ]);
        }
    }
}
