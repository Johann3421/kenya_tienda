<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeVascoToKenyaBarcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Actualizar todos los códigos de barras que comienzan con VASCO para que comiencen con KENYA
        DB::table('soportes')->where('codigo_barras', 'like', 'VASCO%')->update([
            'codigo_barras' => DB::raw("REPLACE(codigo_barras, 'VASCO', 'KENYA')")
        ]);

        DB::table('pedidos')->where('codigo_barras', 'like', 'VASCO%')->update([
            'codigo_barras' => DB::raw("REPLACE(codigo_barras, 'VASCO', 'KENYA')")
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir cambios si es necesario
        DB::table('soportes')->where('codigo_barras', 'like', 'KENYA%')->update([
            'codigo_barras' => DB::raw("REPLACE(codigo_barras, 'KENYA', 'VASCO')")
        ]);

        DB::table('pedidos')->where('codigo_barras', 'like', 'KENYA%')->update([
            'codigo_barras' => DB::raw("REPLACE(codigo_barras, 'KENYA', 'VASCO')")
        ]);
    }
}
