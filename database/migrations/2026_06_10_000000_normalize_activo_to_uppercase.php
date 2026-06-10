<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tables = ['categorias', 'modelos', 'marcas', 'banners', 'soportes', 'ventas', 'users', 'configuraciones', 'apis', 'manuales'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'activo')) {
                DB::statement("UPDATE {$table} SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
                DB::statement("UPDATE {$table} SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
            }
        }
    }

    public function down()
    {
        // No reversible needed
    }
};
