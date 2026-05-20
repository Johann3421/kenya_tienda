<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaginaEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pagina_estados')) {
            Schema::create('pagina_estados', function (Blueprint $table) {
                $table->id();
                $table->string('ruta')->nullable();
                $table->string('nombre')->nullable();
                $table->string('estado')->default('activo');
                $table->timestamp('fin_mantenimiento')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagina_estados');
    }
}
