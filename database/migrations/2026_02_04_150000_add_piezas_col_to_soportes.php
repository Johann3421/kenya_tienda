<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPiezasColToSoportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soportes', function (Blueprint $table) {
            // Agregar columnas para múltiples piezas retiradas y piezas adicionales
            if (!Schema::hasColumn('soportes', 'piezas_retiradas_multiple')) {
                $table->json('piezas_retiradas_multiple')->nullable()->after('pieza_falla');
            }
            if (!Schema::hasColumn('soportes', 'piezas_adicionales_texto')) {
                $table->text('piezas_adicionales_texto')->nullable()->after('piezas_retiradas_multiple');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soportes', function (Blueprint $table) {
            if (Schema::hasColumn('soportes', 'piezas_retiradas_multiple')) {
                $table->dropColumn('piezas_retiradas_multiple');
            }
            if (Schema::hasColumn('soportes', 'piezas_adicionales_texto')) {
                $table->dropColumn('piezas_adicionales_texto');
            }
        });
    }
}
