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
        Schema::create('jurisdicciones', function (Blueprint $table) {
            $table->string('idjurisdiccion', 3)->primary();
            $table->string('idestado', 3);
            $table->string('jurisdiccion', 100);

            $table->foreign('idestado')->references('idestado')->on('estados')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurisdicciones');
    }
};
