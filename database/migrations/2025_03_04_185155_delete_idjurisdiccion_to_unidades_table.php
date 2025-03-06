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
        Schema::table('unidades', function (Blueprint $table) {
            $table->dropForeign(['idjurisdiccion']);
            $table->dropColumn(['idjurisdiccion']);

            $table->string('nointerior', 40)->change();
            $table->string('noexterior', 40)->change();

            $table->string('latitud', 40)->change();
            $table->string('longitud', 40)->change();

            $table->string('nombre', 200)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->string('idjurisdiccion', 3)->after('idnivel_atencion');

            $table->foreign('idjurisdiccion')->references('idjurisdiccion')->on('jurisdicciones');

            $table->string('nointerior', 20)->change();
            $table->string('noexterior', 20)->change();

            $table->decimal('latitud', 10, 8)->change();
            $table->decimal('longitud', 11, 8)->change();

            $table->string('nombre', 100)->change();
        });
    }
};
