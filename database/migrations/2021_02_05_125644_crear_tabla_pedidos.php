<?php

use Brick\Math\BigInteger;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearTablaPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras', 20)->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('cliente_id')->unsined();
            $table->dateTime('fecha_registro');
            $table->date('fecha_entrega')->nullable();
            $table->string('tipo_entrega', 20)->comment('LOCAL, DOMICILIO, AGENCIA');
            $table->string('estado_entrega', 10)->comment('P1:PEDIDO REALIZADO, P2:PEDIDO EN TRANSITO, P3:PEDIDO EN TIENDA, P4:PEDIDO ENTREGADO, P5:PEDIDO CANCELADO');
            $table->string('forma_envio', 100)->comment('POR AGENCIA, POR OLVA, OTROS MEDIOS')->nullable();
            $table->text('detalle_envio')->nullable();
            $table->string('guia_remision', 100)->nullable();
            $table->double('acuenta', 9, 2)->default(0.00);
            $table->double('costo_total', 9, 2)->default(0.00);
            $table->double('saldo_total', 9, 2)->default(0.00);
            $table->text('observacion')->nullable();
            $table->char('activo', 2)->default('SI');
            $table->timestamps();
        });

        DB::statement("CREATE VIEW view_pedidos AS SELECT
            SUM(
                IF(estado_entrega = 'P1', 1, 0)
            ) AS realizado,
            SUM(
                IF(estado_entrega = 'P2', 1, 0)
            ) AS transito,
            SUM(
                IF(estado_entrega = 'P3', 1, 0)
            ) AS tienda,
            SUM(
                IF(estado_entrega = 'P4', 1, 0)
            ) AS entregado,
            SUM(
                IF(estado_entrega = 'P5', 1, 0)
            ) AS cancelado
            FROM pedidos;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
        DB::statement("DROP VIEW view_pedidos");
    }
}
