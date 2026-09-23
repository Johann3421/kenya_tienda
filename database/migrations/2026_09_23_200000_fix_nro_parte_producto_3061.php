<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixNroParteProducto3061 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('productos')) {
            DB::table('productos')
                ->where('id', 3061)
                ->orWhere('nro_parte', 'EZENT T700')
                ->update([
                    'nro_parte' => 'E7CT6OWNHPXO3B5PV6',
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('productos')) {
            DB::table('productos')
                ->where('id', 3061)
                ->where('nro_parte', 'E7CT6OWNHPXO3B5PV6')
                ->update([
                    'nro_parte' => 'EZENT T700',
                    'updated_at' => now(),
                ]);
        }
    }
}
