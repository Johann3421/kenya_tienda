<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Garantia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garantia', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_venta');
            $table->string('garantia');
            $table->date('fecha_Vencimiento');
            $table->string('serie');
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
        Schema::dropIfExists('garantia');
    }
}
