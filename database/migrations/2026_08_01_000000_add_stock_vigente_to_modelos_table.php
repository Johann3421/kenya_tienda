<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockVigenteToModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('modelos', 'stock_vigente')) {
            Schema::table('modelos', function (Blueprint $table) {
                $table->integer('stock_vigente')->default(20)->nullable();
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
        if (Schema::hasColumn('modelos', 'stock_vigente')) {
            Schema::table('modelos', function (Blueprint $table) {
                $table->dropColumn('stock_vigente');
            });
        }
    }
}
