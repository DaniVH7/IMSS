<?php

namespace Database\Seeders;

use App\Models\MotivosBaja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class MotivosBajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/motivos_baja.json');

        $motivos_baja = json_decode($json);

        //dd($motivos_baja);

        foreach($motivos_baja as $key => $value) {
            MotivosBaja::create([
                'idmotivo_baja' => $value->idmotivo_baja,
                'motivo_baja' => $value->motivo_baja
            ]);
        }
    }
}
