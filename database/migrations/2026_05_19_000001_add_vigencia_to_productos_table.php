<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Campo vigencia: OFERTADA = visible, SUSPENDIDA = oculto del catálogo
            if (!Schema::hasColumn('productos', 'vigencia')) {
                $table->string('vigencia', 20)->nullable()->after('pagina_web')
                    ->comment('OFERTADA=visible en catálogo, SUSPENDIDA=oculto');
            }
            // Fecha de la última sincronización con Peru Compras
            if (!Schema::hasColumn('productos', 'ficha_sync_at')) {
                $table->timestamp('ficha_sync_at')->nullable()->after('vigencia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['vigencia', 'ficha_sync_at']);
        });
    }
};
