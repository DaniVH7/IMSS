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
        Schema::create('subtipologias', function (Blueprint $table) {
            $table->string('idsubtipologia',3)->primary();
            $table->string('subtipologia', 50);
            $table->string('descripcion_subtipologia', 150);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtipologias');
    }
};
