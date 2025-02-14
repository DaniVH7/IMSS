<?php

namespace Database\Seeders;

use App\Models\Estrato;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class EstratosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/estratos.json');

        $estratos = json_decode($json);

        //dd($estratos);


        foreach($estratos as $key => $value) {
            Estrato::create([
                'idestrato' => $value->idestrato,
                'estrato' => $value->estrato
            ]);
        }
    }
}
