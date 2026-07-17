<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidationStateToClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('clientes', 'estado_validacion')) {
            Schema::table('clientes', function (Blueprint $table) {
                // Ponytail: adding a simple string enum for validation state
                $table->string('estado_validacion', 20)->default('pendiente')->after('tipo');
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
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('estado_validacion');
        });
    }
}
