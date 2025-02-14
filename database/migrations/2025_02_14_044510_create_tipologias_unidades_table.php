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
        Schema::create('tipologias_unidades', function (Blueprint $table) {
            $table->string('idtipologia_unidad', 3)->primary();
            $table->string('tipologia_unidad', 255);
            $table->string('clave_tipologia', 10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipologias_unidades');
    }
};
