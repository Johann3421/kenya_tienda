<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("UPDATE categorias SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE categorias SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE modelos SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE modelos SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE marcas SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE marcas SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE banners SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE banners SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE soportes SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE soportes SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE ventas SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE ventas SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE users SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE users SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE configuraciones SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE configuraciones SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE apis SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE apis SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
        DB::statement("UPDATE manuales SET activo = 'SI' WHERE UPPER(activo) = 'SI' AND activo != 'SI'");
        DB::statement("UPDATE manuales SET activo = 'NO' WHERE UPPER(activo) = 'NO' AND activo != 'NO'");
    }

    public function down()
    {
        // No reversible needed
    }
};
