<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesSoportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_soportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soporte_id')->references('id')->on('soportes')->onDelete('cascade');
            $table->string('descripcion');
            $table->double('precio', 9, 2)->default(0.00);
            $table->double('descuento', 9, 2)->default(0.00);
            $table->smallInteger('cantidad');
            $table->double('importe', 9, 2)->default(0.00);
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
        Schema::dropIfExists('detalles_soportes');
    }
}
