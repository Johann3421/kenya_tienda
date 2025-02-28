<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nombre_secundario')->nullable();
            $table->text('descripcion')->nullable();
            $table->longText('especificaciones')->nullable();
            //$table->string('modelo_id')->nullable();
            $table->string('nro_parte')->nullable();
            $table->string('procesador')->nullable();
            $table->string('ram')->nullable();
            $table->string('almacenamiento')->nullable();
            $table->string('conectividad')->nullable();
            $table->string('conectividad_wlan')->nullable();
            $table->string('conectividad_usb')->nullable();
            $table->string('video_vga')->nullable();
            $table->string('video_hdmi')->nullable();
            $table->string('sistema_operativo')->nullable();
            $table->string('unidad_optica')->nullable();
            $table->string('teclado')->nullable();
            $table->string('mouse')->nullable();
            $table->string('suite_ofimatica')->nullable();
            $table->string('garantia_de_fabrica')->nullable();
            $table->string('unidad')->nullable();
            $table->string('marca')->nullable();
            $table->string('empaque_de_fabrica')->nullable();
            $table->string('certificacion')->nullable();
            $table->string('moneda')->nullable();
            $table->double('precio_unitario', 15, 2)->nullable();
            $table->double('precio_compra', 15, 2)->nullable();
            $table->double('precio_anterior', 15, 2)->nullable();
            $table->string('tipo_afectacion', 10)->nullable()->comment('10:gravada, 20:exonerada');
            $table->string('tipo_afectacion_compra', 10)->nullable()->comment('10:gravada, 20:exonerada');
            $table->smallInteger('stock_inicial')->nullable();
            $table->smallInteger('stock_minimo')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('codigo_barras')->nullable();
            $table->string('codigo_interno')->nullable();
            $table->string('codigo_sunat')->nullable();
            $table->string('linea_producto')->nullable();
            //$table->char('incluye_igv', 2)->default('NO');
            $table->char('incluye_igv', 2)->nullable();
            $table->string('imagen_1')->nullable();
            $table->string('imagen_2')->nullable();
            $table->string('imagen_3')->nullable();
            $table->string('imagen_4')->nullable();
            $table->string('imagen_5')->nullable();
            $table->bigInteger('categoria_id')->nullable();
            $table->bigInteger('marca_id')->nullable();
            $table->char('pagina_web', 2)->default('NO');
            $table->foreignId('modelo_id')->references('id')->on('modelos')->onDelete('restrict');
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
        Schema::dropIfExists('productos');
    }
}
