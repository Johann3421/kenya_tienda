<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManuales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->string('link',255);
            $table->char('activo', 2)->default('Si')->comment("Si=si, No=no");
            $table->foreignId('producto_id')->references('id')->on('productos')->onDelete('restrict');
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
        Schema::dropIfExists('manuales');
    }
}
