<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class ProductoDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('producto_drivers');
        Schema::create('producto_drivers', function (Blueprint $table) {
            $table->id();
            $table->string('categoria', 150);
            $table->string('nombre', 150);
            $table->string('version', 150);
            $table->date('liberado');
            $table->double('tamano');
            $table->string('unidad', 50);
            $table->string('gravedad', 150);
            $table->char('activo', 2)->default('Si')->comment("Si=si, No=no");
            $table->text('link');
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
        // Schema::table('producto_drivers', function (Blueprint $table) {
        //     $table->dropForeign('producto_drivers_productos_ID_foreign');
        // });
    }
}
