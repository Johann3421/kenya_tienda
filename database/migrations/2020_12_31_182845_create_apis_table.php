<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CreateApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->text('url');
            $table->text('token');
            $table->text('host');
            $table->text('observacion')->nullable();
            $table->char('activo', 2)->default('SI');
        });

        DB::table('apis')->insert([
            ['descripcion' => 'DNI', 'url' => 'http://apiperu.dev/api/dni', 'token' => '54eb479a6c436dbefca61ea8e85e1884ced95a4591243fbfc3b7a4a79028ea3d', 'host' => 'http://consulta.apiperu.pe', 'observacion' => 'PARA BUSCAR DATOS DE UN DNI', 'activo' => 'SI'],
            ['descripcion' => 'RUC', 'url' => 'http://apiperu.dev/api/ruc', 'token' => '54eb479a6c436dbefca61ea8e85e1884ced95a4591243fbfc3b7a4a79028ea3d', 'host' => 'http://consulta.apiperu.pe', 'observacion' => 'PARA BUSCAR DATOS DE UN RUC', 'activo' => 'SI'],
            ['descripcion' => 'FACTURA', 'url' => 'https://gsoft.factmype.online/api/documents', 'token' => 'cVzZKj5ykzV7h2A15jvQ4GBYhvWzg0swDGSQV8GSaBQVx1JjwD', 'host' => 'https://gsoft.factmype.online', 'observacion' => 'PARA GENERAR FACTURA', 'activo' => 'SI'],
            ['descripcion' => 'ANULAR-FACTURA', 'url' => 'https://gsoft.factmype.online/api/voided', 'token' => 'cVzZKj5ykzV7h2A15jvQ4GBYhvWzg0swDGSQV8GSaBQVx1JjwD', 'host' => 'https://gsoft.factmype.online', 'observacion' => 'PARA ANULAR FACTURA', 'activo' => 'SI'],
            ['descripcion' => 'STATUS-FACTURA', 'url' => 'https://gsoft.factmype.online/api/voided/status', 'token' => 'cVzZKj5ykzV7h2A15jvQ4GBYhvWzg0swDGSQV8GSaBQVx1JjwD', 'host' => 'https://gsoft.factmype.online', 'observacion' => 'PARA VER EL ESTADO DEL ANULADO DE FACTURA', 'activo' => 'SI'],
            ['descripcion' => 'ANULAR-BOLETA', 'url' => 'https://gsoft.factmype.online/api/summaries', 'token' => 'cVzZKj5ykzV7h2A15jvQ4GBYhvWzg0swDGSQV8GSaBQVx1JjwD', 'host' => 'https://gsoft.factmype.online', 'observacion' => 'PARA ANULAR BOLETA', 'activo' => 'SI'],
            ['descripcion' => 'STATUS-BOLETA', 'url' => 'https://gsoft.factmype.online/api/summaries/status', 'token' => 'cVzZKj5ykzV7h2A15jvQ4GBYhvWzg0swDGSQV8GSaBQVx1JjwD', 'host' => 'https://gsoft.factmype.online', 'observacion' => 'PARA VER EL ESTADO DEL ANULADO DE BOLETA', 'activo' => 'SI'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apis');
    }
}
