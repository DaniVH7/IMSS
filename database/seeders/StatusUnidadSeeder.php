<?php

namespace Database\Seeders;

use App\Models\StatusUnidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class StatusUnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/status_unidades.json');

        $status_unidades = json_decode($json);

        //dd($status_unidades);

        foreach($status_unidades as $key => $value) {
            StatusUnidad::create([
                'idstatus_unidad' => $value->idstatus_unidad,
                'status_unidad' => $value->status_unidad
            ]);
        }
    }
}
