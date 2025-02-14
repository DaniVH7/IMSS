<?php

namespace Database\Seeders;

use App\Models\Localidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class LocalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/localidades.json');

        $localidades = json_decode($json);

        foreach($localidades as $key => $value) {
            Localidad::create([
                'idlocalidad' => $value->idlocalidad,
                'localidad' => $value->localidad,
                'idestado' => $value->idestado,
                'idmunicipio' => $value->idmunicipio
            ]);
        }
    }
}
