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
            if (!Schema::hasColumn('soportes', 'falla')) {
                $table->string('falla')->nullable();
            }
            if (!Schema::hasColumn('soportes', 'diagnostico')) {
                $table->string('diagnostico')->nullable();
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
            $table->dropColumn('falla');
            $table->dropColumn('diagnostico');
        });
    }
}
