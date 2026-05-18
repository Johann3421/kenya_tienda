<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagenFichaToProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'imagen')) {
                $table->string('imagen')->nullable()->after('imagen_5');
            }
            if (!Schema::hasColumn('productos', 'ficha')) {
                $table->string('ficha')->nullable()->after('imagen');
            }
            if (!Schema::hasColumn('productos', 'categoria')) {
                $table->string('categoria')->nullable()->after('ficha');
            }
            if (!Schema::hasColumn('productos', 'marca')) {
                $table->string('marca')->nullable()->after('categoria');
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
