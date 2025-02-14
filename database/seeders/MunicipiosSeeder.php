<?php

namespace Database\Seeders;

use App\Models\Municipio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class MunicipiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/municipios.json');

        $municipios = json_decode($json);

        foreach($municipios as $key => $value) {
            Municipio::create([
                'idmunicipio' => $value->idmunicipio,
                'municipio' => $value->municipio,
                'idestado' => $value->idestado,
                'idjurisdiccion' => $value->idjurisdiccion
            ]);
        }
    }
}
