<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->text('imagen')->nullable();
            $table->string('titulo', 100)->nullable();
            $table->string('titulo_color', 10)->nullable();
            $table->string('descripcion')->nullable();
            $table->text('contenido')->nullable();
            $table->longText('link')->nullable();
            $table->string('link_nombre')->nullable();
            $table->char('activo', 2)->default('SI');
            $table->timestamps();
        });

        DB::table('banners')->insert([
            ['imagen' => '', 'titulo' => 'KENYA', 'titulo_color' => '#f39926', 'descripcion' => 'Especialistas en soporte técnico', 'contenido' => 'Somos profesionales que le brindarán la garantía y la seguridad que su empresa y hogar necesitan.', 'link' => '#productos', 'link_nombre' => 'Leer Más...', 'activo' => 'SI'],
            ['imagen' => '', 'titulo' => '¿Problemas con su computadora?', 'titulo_color' => '', 'descripcion' => '', 'contenido' => 'Nosotros nos encargamos de los requerimientos o problemas en sus equipos de cómputo.', 'link' => '#productos', 'link_nombre' => 'Leer Más...', 'activo' => 'SI'],
            ['imagen' => '', 'titulo' => 'Venta de equipos y accesorios de cómputo', 'titulo_color' => '', 'descripcion' => '', 'contenido' => 'Somos distribuidores principales del mercado, comercializamos equipos de calidad para garantizar su compra', 'link' => '#productos', 'link_nombre' => 'Leer Más...', 'activo' => 'SI']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
