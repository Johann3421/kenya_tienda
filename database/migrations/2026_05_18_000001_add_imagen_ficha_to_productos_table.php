<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagenFichaToProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            // PostgreSQL: ->after() no existe, se omite
            if (!Schema::hasColumn('productos', 'imagen')) {
                $table->string('imagen')->nullable();
            }
            if (!Schema::hasColumn('productos', 'ficha')) {
                $table->string('ficha')->nullable();
            }
            if (!Schema::hasColumn('productos', 'categoria')) {
                $table->string('categoria')->nullable();
            }
            if (!Schema::hasColumn('productos', 'marca')) {
                $table->string('marca')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['imagen', 'ficha', 'categoria', 'marca']);
        });
    }
}
