<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('productos', 'ficha_editada_localmente')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->boolean('ficha_editada_localmente')->default(false)->comment('Bandera para evitar que la API sobrescriba ediciones locales');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('ficha_editada_localmente');
        });
    }
};
