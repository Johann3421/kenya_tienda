<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->foreignId('categoria_id')->references('id')->on('categorias')->onDelete('restrict');
            $table->char('activo', 2)->default('Si')->comment("Si=si, No=no");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_modelos');
    }
}
