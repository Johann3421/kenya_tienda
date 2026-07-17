<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesToProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('productos', 'precio_referencial')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->decimal('precio_referencial', 10, 2)->nullable();
                $table->decimal('precio_especial', 10, 2)->nullable();
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
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['precio_referencial', 'precio_especial']);
        });
    }
}
