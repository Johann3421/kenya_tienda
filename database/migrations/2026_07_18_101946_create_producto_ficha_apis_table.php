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
        Schema::create('producto_ficha_apis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('codigo_pc')->nullable()->index();
            $table->json('datos_crudos')->nullable(); // Guardar todas las especificaciones de la API
            $table->string('pdf_url')->nullable();
            $table->json('imagenes')->nullable(); // Guardar array de imagenes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_ficha_apis');
    }
};
