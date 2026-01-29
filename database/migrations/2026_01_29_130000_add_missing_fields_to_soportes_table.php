<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToSoportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soportes', function (Blueprint $table) {
            if (!Schema::hasColumn('soportes', 'nro_parte')) {
                $table->string('nro_parte', 100)->nullable()->after('serie');
            }
            if (!Schema::hasColumn('soportes', 'numero_caso')) {
                $table->string('numero_caso', 255)->nullable()->after('reporte_tecnico');
            }
            if (!Schema::hasColumn('soportes', 'pdf_link')) {
                $table->string('pdf_link', 500)->nullable()->after('numero_caso');
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
            $table->dropColumn(['nro_parte', 'numero_caso', 'pdf_link']);
        });
    }
}
