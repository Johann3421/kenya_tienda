<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductoIdToTransacciones extends Migration
{
    public function up()
    {
        Schema::table('detalles_ventas', function (Blueprint $table) {
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
        });

        Schema::table('detalles_pedidos', function (Blueprint $table) {
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('detalles_ventas', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
        });

        Schema::table('detalles_pedidos', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
        });
    }
}
