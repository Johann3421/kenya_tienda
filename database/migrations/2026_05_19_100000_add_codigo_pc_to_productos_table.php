<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'codigo_pc')) {
                $table->string('codigo_pc', 60)->nullable()->after('nro_parte')
                    ->comment('Código único de Peru Compras (nro_parte de la ficha técnica oficial)');
            }
        });

        // Índice para búsquedas rápidas por codigo_pc
        try {
            Schema::table('productos', function (Blueprint $table) {
                $table->index('codigo_pc', 'idx_productos_codigo_pc');
            });
        } catch (\Exception $e) {
            // El índice ya existe — ignorar
        }
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropIndex('idx_productos_codigo_pc');
            $table->dropColumn('codigo_pc');
        });
    }
};
