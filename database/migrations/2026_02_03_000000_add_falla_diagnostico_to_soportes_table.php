<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFallaDiagnosticoToSoportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soportes', function (Blueprint $table) {
            $table->string('falla')->nullable()->after('pieza_falla');
            $table->string('diagnostico')->nullable()->after('falla');
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
            $table->dropColumn('falla');
            $table->dropColumn('diagnostico');
        });
    }
}
