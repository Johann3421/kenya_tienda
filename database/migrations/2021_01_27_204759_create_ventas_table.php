<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->char('nota_credito', 2)->default('NO');
            $table->string('comprobante', 100);
            $table->string('serie', 10);
            $table->string('numero', 10);
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('cliente_id')->unsined();
            $table->string('medio_pago', 100);
            $table->double('credito', 9, 2)->default(0.00);
            $table->double('comision', 9, 2)->default(0.00);
            $table->double('subtotal', 9, 2)->default(0.00);
            $table->double('acuenta', 9, 2)->default(0.00);
            $table->double('costo_venta', 9, 2)->default(0.00);
            $table->double('saldo_total', 9, 2)->default(0.00);
            $table->dateTime('fecha_registro');
            $table->string('observacion')->nullable();
            $table->char('activo', 2)->default('SI');
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
        Schema::dropIfExists('ventas');
    }
}
