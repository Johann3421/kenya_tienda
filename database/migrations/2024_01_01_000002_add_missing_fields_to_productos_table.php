<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Columnas agregadas manualmente en MySQL legacy
            $columnas = [
                'Tipo de suministro', 'Tipo de panel', 'Modelo', 'Color', 
                'Descripción', 'Rendimiento', 'Garantia', 'Sistema RAEE', 
                'Certificaciones', 'Empaque', 'Número de parte', 'Dimensiones', 
                'especificaciones_json', 'filtros_ids'
            ];

            foreach ($columnas as $col) {
                if (!Schema::hasColumn('productos', $col)) {
                    $table->text($col)->nullable();
                }
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
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'Tipo de suministro', 'Tipo de panel', 'Modelo', 'Color', 
                'Descripción', 'Rendimiento', 'Garantia', 'Sistema RAEE', 
                'Certificaciones', 'Empaque', 'Número de parte', 'Dimensiones', 
                'especificaciones_json', 'filtros_ids'
            ]);
        });
    }
};
