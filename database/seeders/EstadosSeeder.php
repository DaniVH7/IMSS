<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/estados.json');

        $estados = json_decode($json);

        foreach($estados as $key => $value) {
            Estado::create([
                'idestado' => $value->idestado,
                'estado' => $value->estado,
                'siglas' => $value->siglas
            ]);
        }
    }
}
