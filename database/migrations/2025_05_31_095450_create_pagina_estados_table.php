<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

public function up()
{
    Schema::create('pagina_estados', function (Blueprint $table) {
        $table->id();
        $table->string('ruta')->unique();
        $table->string('nombre');
        $table->enum('estado', ['activo', 'mantenimiento'])->default('activo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagina_estados');
    }
};
