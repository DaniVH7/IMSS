<?php

namespace Database\Seeders;

use App\Models\TipoEstablecimiento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class TiposEstablecimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/tipos_establecimiento.json');

        $tipos_establecimiento = json_decode($json);

        //dd($tipos_establecimiento);

        foreach($tipos_establecimiento as $key => $value) {
            TipoEstablecimiento::create([
                'idtipo_establecimiento' => $value->idtipo_establecimiento,
                'tipo_establecimiento' => $value->tipo_establecimiento
            ]);
        }
    }
}
