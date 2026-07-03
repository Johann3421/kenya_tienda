<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertasWebTable extends Migration
{
    public function up()
    {
        Schema::create('ofertas_web', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 100);
            $table->string('descripcion');
            $table->string('imagen');
            $table->string('color_fondo', 30)->nullable();
            $table->string('link', 255)->nullable();
            $table->char('activo', 2)->default('SI');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ofertas_web');
    }
}
