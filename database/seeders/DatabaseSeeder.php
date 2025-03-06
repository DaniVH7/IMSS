<?php

namespace Database\Seeders;

use App\Models\Estrato;
use App\Models\Institucion;
use App\Models\TipoAdministracion;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            EstratosSeeder::class,
            EstadosSeeder::class,
            JurisdiccionesSeeder::class,
            InstitucionesSeeder::class,
            MotivosBajaSeeder::class,
            MunicipiosSeeder::class,
            NivelAtencionSeeder::class,
            StatusUnidadSeeder::class,
            SubtipologiasSeeder::class,
            TipologiasUnidadesSeeder::class,
            TiposAdministracionSeeder::class,
            TiposAsentamientoSeeder::class,
            TiposEstablecimientoSeeder::class,
            TiposUnidadesSeeder::class,
            TiposVialidadSeeder::class,
            LocalidadesSeeder::class,
            UnidadesSeeder::class,
            //UnidadesHospitalesSeeder::class
        ]);
    }
}
