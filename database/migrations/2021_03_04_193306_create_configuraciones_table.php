<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateConfiguracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('descripcion');
            $table->string('archivo', 20)->nullable();
            $table->string('archivo_nombre', 100)->nullable();
            $table->string('archivo_ruta')->nullable();
            $table->char('activo', 2)->default('SI');
        });

        DB::table('configuraciones')->insert([
            ['nombre' => 'ruc_empresa', 'descripcion' => '10447783849', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'nombre_empresa', 'descripcion' => 'GRUPO VASCO', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'contacto_direccion', 'descripcion' => 'Jr. Huallayco - 1135 - Huanuco', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'contacto_email', 'descripcion' => 'acuerdos.marco@kenya.com.pe', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'contacto_telefono', 'descripcion' => '958021778', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'contacto_whatsapp', 'descripcion' => '958021778', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'boleta_titulo', 'descripcion' => 'TITULO DE LA BOLETA', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'logo_sistema', 'descripcion' => '-', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI'],
            ['nombre' => 'logo_codigo_barras', 'descripcion' => '-', 'archivo' => null, 'archivo_nombre' => null, 'archivo_ruta' => null, 'activo' => 'SI']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuraciones');
    }
}
