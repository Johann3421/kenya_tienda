<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pagina_estados', function (Blueprint $table) {
            $table->timestamp('fin_mantenimiento')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pagina_estados', function (Blueprint $table) {
            $table->dropColumn('fin_mantenimiento');
        });
    }
};
