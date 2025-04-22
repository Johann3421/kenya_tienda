<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_banner_medios_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerMediosTable extends Migration
{
    public function up()
    {
        Schema::create('banner_medios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('imagen_path');
            $table->string('url_destino');
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->string('posicion')->default('medio'); // Para diferenciar ubicación
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner_medios');
    }
}
