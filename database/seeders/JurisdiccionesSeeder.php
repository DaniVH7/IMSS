<?php

namespace Database\Seeders;

use App\Models\Jurisdiccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class JurisdiccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/jurisdicciones.json');

        $jurisdicciones = json_decode($json);

        foreach($jurisdicciones as $key => $value) {
            Jurisdiccion::create([
                'idjurisdiccion' => $value->idjurisdiccion,
                'idestado' => $value->idestado,
                'jurisdiccion' => $value->jurisdiccion
            ]);
        }
    }
}
