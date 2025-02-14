<?php

namespace Database\Seeders;

use App\Models\NivelAtencion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class NivelAtencionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/niveles_atencion.json');

        $niveles_atencion = json_decode($json);

        //dd($niveles_atencion);

        foreach($niveles_atencion as $key => $value) {
            NivelAtencion::create([
                'idnivel_atencion' => $value->idnivel_atencion,
                'nivel_atencion' => $value->nivel_atencion
            ]);
        }
    }
}
