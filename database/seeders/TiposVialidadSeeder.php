<?php

namespace Database\Seeders;

use App\Models\TipoVialidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class TiposVialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/tipos_vialidades.json');

        $tipos_vialidades = json_decode($json);

        //dd($tipos_vialidades);

        foreach($tipos_vialidades as $key => $value) {
            TipoVialidad::create([
                'idtipo_vialidad' => $value->idtipo_vialidad,
                'tipo_vialidad' => $value->tipo_vialidad
            ]);
        }
    }
}
