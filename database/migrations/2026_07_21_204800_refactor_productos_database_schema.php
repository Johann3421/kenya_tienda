<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorProductosDatabaseSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Agregar columna JSONB especificaciones_json a la tabla productos si no existe
        if (!Schema::hasColumn('productos', 'especificaciones_json')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->jsonb('especificaciones_json')->nullable();
            });
        }

        // 2. Crear tabla producto_precios
        if (!Schema::hasTable('producto_precios')) {
            Schema::create('producto_precios', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('producto_id');
                $table->string('tipo_cliente')->default('regular'); // regular, canal, gubernamental
                $table->string('moneda')->default('USD'); // USD, PEN
                $table->decimal('precio', 12, 2);
                $table->boolean('incluye_igv')->default(false);
                $table->timestamps();

                $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
                $table->index(['producto_id', 'tipo_cliente']);
            });
        }

        // 3. Crear tabla producto_imagenes
        if (!Schema::hasTable('producto_imagenes')) {
            Schema::create('producto_imagenes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('producto_id');
                $table->string('url');
                $table->integer('orden')->default(0);
                $table->boolean('es_principal')->default(false);
                $table->timestamps();

                $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
                $table->index('producto_id');
            });
        }

        // 4. Crear tabla producto_especificaciones
        if (!Schema::hasTable('producto_especificaciones')) {
            Schema::create('producto_especificaciones', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('producto_id');
                $table->string('clave'); // procesador, ram, almacenamiento, etc.
                $table->string('etiqueta'); // Procesador, Memoria RAM, etc.
                $table->text('valor')->nullable();
                $table->integer('orden')->default(0);
                $table->timestamps();

                $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
                $table->index(['producto_id', 'clave']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_especificaciones');
        Schema::dropIfExists('producto_imagenes');
        Schema::dropIfExists('producto_precios');

        if (Schema::hasColumn('productos', 'especificaciones_json')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('especificaciones_json');
            });
        }
    }
}
