<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedor_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->references('id')->on('pedidos')->onDelete('cascade');
            $table->bigInteger('proveedor_id')->nullable();
            $table->string('ruc', 11)->nullable();
            $table->string('nombres')->nullable();
            $table->string('celular', 9)->nullable();
            $table->text('detalles')->nullable();
            $table->text('observacion')->nullable();
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
        Schema::dropIfExists('proveedor_pedidos');
    }
}
