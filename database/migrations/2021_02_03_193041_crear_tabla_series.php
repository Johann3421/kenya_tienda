<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CrearTablaSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('serie', 4);
            $table->string('comprobante', 2);
            $table->string('tipo', 2);
            $table->char('affected_igv', 2);
            $table->string('forma', 20);
            $table->string('descripcion', 50);
            $table->timestamps();
        });

        DB::table('series')->insert([
            ['serie' => 'B001', 'comprobante' => '03', 'tipo' => 'B', 'affected_igv'=>'20', 'forma'=>'EXONERADA', 'descripcion' => 'BOLETA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serie' => 'F001', 'comprobante' => '01', 'tipo' => 'F', 'affected_igv'=>'10', 'forma'=>'GRAVADA', 'descripcion' => 'FACTURA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serie' => 'F002', 'comprobante' => '01', 'tipo' => 'F', 'affected_igv'=>'10', 'forma'=>'GRAVADA', 'descripcion' => 'FACTURA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serie' => 'F003', 'comprobante' => '01', 'tipo' => 'F', 'affected_igv'=>'20', 'forma'=>'EXONERADA', 'descripcion' => 'FACTURA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serie' => '0001', 'comprobante' => '00', 'tipo' => 'CI', 'affected_igv'=>'10', 'forma'=>'GRAVADA', 'descripcion' => 'OTROS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series');
    }
}
