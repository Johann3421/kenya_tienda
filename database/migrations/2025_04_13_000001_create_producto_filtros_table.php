<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('producto_filtros')) {
            Schema::create('producto_filtros', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('producto_id');
                $table->unsignedBigInteger('aside_id');
                $table->string('opcion', 255)->nullable();
                $table->timestamps();

                $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
                $table->foreign('aside_id')->references('id')->on('asides')->onDelete('cascade');

                $table->index('producto_id');
                $table->index('aside_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_filtros');
    }
};
