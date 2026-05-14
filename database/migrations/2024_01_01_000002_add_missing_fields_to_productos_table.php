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
            // Se agregan campos que existían en la base de datos MySQL original
            // pero que nunca se registraron en ninguna migración de Laravel.
            if (!Schema::hasColumn('productos', 'Tipo de suministro')) {
                $table->string('Tipo de suministro')->nullable();
            }
            if (!Schema::hasColumn('productos', 'Tipo de panel')) {
                $table->string('Tipo de panel')->nullable();
            }
            if (!Schema::hasColumn('productos', 'Modelo')) {
                $table->string('Modelo')->nullable();
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
            $table->dropColumn(['Tipo de suministro', 'Tipo de panel', 'Modelo']);
        });
    }
};
