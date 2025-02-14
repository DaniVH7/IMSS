<?php

namespace Database\Seeders;


use App\Models\Subtipologia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class SubtipologiasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/subtipologias.json');

        $subtipologias = json_decode($json);

        //dd($subtipologias);

        foreach ($subtipologias as $subtipologia) {
            Subtipologia::create([
                'idsubtipologia' => $subtipologia->idsubtipologia,
                'subtipologia' => $subtipologia->subtipologia,
                'descripcion_subtipologia' => $subtipologia->descripcion_subtipologia
            ]);
        }
    }
}
