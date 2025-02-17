<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('unidades', function (Blueprint $table) {
        $table->string('idestado', 3);
        $table->string('idmunicipio', 3);
        $table->string('idlocalidad', 4);
        $table->string('clues', 12)->primary();
        $table->string('idtipo_unidad', 3);
        $table->string('idtipologia_unidad', 3);
        $table->string('idinstitucion', 3);
        $table->string('idestrato', 3);
        $table->string('idtipo_vialidad', 3);
        $table->string('idtipo_asentamiento', 3);
        $table->string('idtipo_administracion', 3);
        $table->string('idstatus_unidad', 3);
        $table->string('idmotivo_baja', 3)->nullable();
        $table->string('idtipo_establecimiento', 3);
        $table->string('idsubtipologia', 3);
        $table->string('idnivel_atencion', 3);
        $table->string('idjurisdiccion', 3);

        $table->string('nombre', 100);
        $table->string('vialidad', 100);
        $table->string('asentamiento', 100);
        $table->string('nointerior', 20)->nullable();
        $table->string('noexterior', 20)->nullable();
        $table->string('cp', 10);
        $table->decimal('latitud', 10, 8)->nullable();
        $table->decimal('longitud', 11, 8)->nullable();
        $table->string('email')->nullable();
        $table->string('telefono', 20)->nullable();
        $table->date('inicio_operacion')->nullable();
        $table->string('horarios')->nullable();
        $table->string('nombre_responsable', 100)->nullable();
        $table->string('pa_responsable', 50)->nullable();
        $table->string('sa_responsable', 50)->nullable();
        $table->string('cedula_responsable', 20)->nullable();

        // Llaves foráneas
        $table->foreign(['idestado', 'idmunicipio', 'idlocalidad'])->references(['idestado', 'idmunicipio', 'idlocalidad'])->on('localidades');
        $table->foreign('idestado')->references('idestado')->on('estados');
        $table->foreign('idmunicipio')->references('idmunicipio')->on('municipios');
        $table->foreign('idjurisdiccion')->references('idjurisdiccion')->on('jurisdicciones');
        $table->foreign('idinstitucion')->references('idinstitucion')->on('instituciones');
        $table->foreign('idestrato')->references('idestrato')->on('estratos');
        $table->foreign('idtipo_unidad')->references('idtipo_unidad')->on('tipos_unidades');
        $table->foreign('idtipologia_unidad')->references('idtipologia_unidad')->on('tipologias_unidades');
        $table->foreign('idtipo_vialidad')->references('idtipo_vialidad')->on('tipos_vialidades');
        $table->foreign('idtipo_asentamiento')->references('idtipo_asentamiento')->on('tipos_asentamientos');
        $table->foreign('idtipo_administracion')->references('idtipo_administracion')->on('tipos_administracion');
        $table->foreign('idstatus_unidad')->references('idstatus_unidad')->on('status_unidades');
        $table->foreign('idmotivo_baja')->references('idmotivo_baja')->on('motivos_baja')->nullable();
        $table->foreign('idtipo_establecimiento')->references('idtipo_establecimiento')->on('tipos_establecimiento');
        $table->foreign('idsubtipologia')->references('idsubtipologia')->on('subtipologias');
        $table->foreign('idnivel_atencion')->references('idnivel_atencion')->on('niveles_atencion');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
