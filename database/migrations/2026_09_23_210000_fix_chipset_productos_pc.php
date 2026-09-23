<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixChipsetProductosPc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('productos') && Schema::hasColumn('productos', 'chipset')) {
            // 1. Asignar 'Intel' a computadoras con procesador Intel o modelos corporativos Kenya sin chipset
            DB::table('productos')
                ->where(function ($q) {
                    $q->whereNull('chipset')
                      ->orWhereIn('chipset', ['', 'No especificado', 'NO ESPECIFICADO', '-', 'NULL', 'null']);
                })
                ->where(function ($q) {
                    $q->where('procesador', 'like', '%intel%')
                      ->orWhere('procesador', 'like', '%core%')
                      ->orWhere('procesador', 'like', '%i3%')
                      ->orWhere('procesador', 'like', '%i5%')
                      ->orWhere('procesador', 'like', '%i7%')
                      ->orWhere('procesador', 'like', '%i9%')
                      ->orWhere('nombre', 'like', '%EZENT%')
                      ->orWhere('nombre', 'like', '%PROWORK%')
                      ->orWhere('nombre', 'like', '%OFISZU%')
                      ->orWhere('nombre', 'like', '%GENWORK%')
                      ->orWhere('nombre', 'like', '%RAITO%');
                })
                ->update([
                    'chipset' => 'Intel',
                    'updated_at' => now(),
                ]);

            // 2. Asignar 'AMD' a computadoras con procesador AMD sin chipset
            DB::table('productos')
                ->where(function ($q) {
                    $q->whereNull('chipset')
                      ->orWhereIn('chipset', ['', 'No especificado', 'NO ESPECIFICADO', '-', 'NULL', 'null']);
                })
                ->where(function ($q) {
                    $q->where('procesador', 'like', '%amd%')
                      ->orWhere('procesador', 'like', '%ryzen%')
                      ->orWhere('procesador', 'like', '%athlon%');
                })
                ->update([
                    'chipset' => 'AMD',
                    'updated_at' => now(),
                ]);

            // 3. Específicamente asegurar producto 2943
            DB::table('productos')
                ->where('id', 2943)
                ->update([
                    'chipset' => 'Intel',
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
        // No-op revert
    }
}
