<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega la columna `descripcion_2` a la tabla `productos`.
 *
 * Esta columna almacena las specs técnicas del procesador en formato compacto:
 *   "20 Núcleos, 28 Hilos, 2.1 GHz Up To 5.4 GHz, Intel UHD 770, LGA1700, L2: 28MB, L3: 33MB, 65W, 2024"
 *
 * Se rellena automáticamente con el script enriquecer_procesadores.py.
 */
class AddDescripcion2ToProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Columna de texto libre, nullable, después de `descripcion`
            if (!Schema::hasColumn('productos', 'descripcion_2')) {
                $table->text('descripcion_2')->nullable()->after('descripcion');
            }
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'descripcion_2')) {
                $table->dropColumn('descripcion_2');
            }
        });
    }
}
