<?php

namespace Database\Seeders;

use App\Models\TipoAsentamiento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class TiposAsentamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/tipos_asentamientos.json');

        $tiposAsentamiento = json_decode($json);

        //dd($tiposAsentamiento);

        foreach($tiposAsentamiento as $key => $value) {
            TipoAsentamiento::create([
                'idtipo_asentamiento' => $value->idtipo_asentamiento,
                'tipo_asentamiento' => $value->tipo_asentamiento
            ]);
        }
    }
}
