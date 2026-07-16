<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSoportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soportes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras', 20)->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('servicio', 20);
            $table->string('estado', 10);
            $table->string('equipo', 100);
            $table->string('marca', 100);
            $table->string('modelo', 50);
            $table->string('serie', 50);
            $table->text('descripcion');
            $table->text('accesorios');
            $table->double('acuenta', 9, 2)->default(0.00);
            $table->double('costo_servicio', 9, 2)->default(0.00);
            $table->double('saldo_total', 9, 2)->default(0.00);
            $table->dateTime('fecha_registro');
            $table->dateTime('fecha_entrega');
            $table->text('observacion')->nullable();
            $table->text('reporte_tecnico')->nullable();
            $table->char('confirmar_reparacion', 2)->nullable();
            $table->char('solo_diagnostico', 2)->default('NO');
            $table->char('activo', 2)->default('SI');
            $table->char('facturado', 2)->default('NO');
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
        Schema::dropIfExists('soportes');
        DB::statement("DROP VIEW view_soportes");
    }
}
