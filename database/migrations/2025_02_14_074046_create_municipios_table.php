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
        Schema::create('municipios', function (Blueprint $table) {
            $table->string('idmunicipio', 3)->primary();
            $table->string('municipio', 100);
            $table->string('idestado', 3);
            $table->string('idjurisdiccion', 3);
            $table->foreign('idestado')->references('idestado')->on('estados');
            $table->foreign('idjurisdiccion')->references('idjurisdiccion')->on('jurisdicciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
