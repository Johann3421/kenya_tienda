<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPiezasRetiradasToSoportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soportes', function (Blueprint $table) {
            if (!Schema::hasColumn('soportes', 'pieza_retirada')) {
                $table->string('pieza_retirada', 255)->nullable();
            }
            if (!Schema::hasColumn('soportes', 'pieza_serie')) {
                $table->string('pieza_serie', 100)->nullable();
            }
            if (!Schema::hasColumn('soportes', 'pieza_falla')) {
                $table->text('pieza_falla')->nullable();
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
            $table->dropColumn(['pieza_retirada', 'pieza_serie', 'pieza_falla']);
        });
    }
}
