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
        Schema::create('localidades', function (Blueprint $table) {
            $table->string('idlocalidad', 4);
            $table->string('idestado', 3);
            $table->string('idmunicipio', 3);
            $table->string('localidad', 100);
            $table->primary(['idestado', 'idmunicipio', 'idlocalidad']); // Índice único combinado

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localidades');
    }
};
