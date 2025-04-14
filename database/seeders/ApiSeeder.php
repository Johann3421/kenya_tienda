<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apis')->insert([
            'descripcion' => 'DNI',
            'host' => 'http://consulta.apiperu.pe',
            'url' => 'http://apiperu.dev/api/dni',
            'token' => '54eb479a6c436dbefca61ea8e85e1884ced95a4591243fbfc3b7a4a79028ea3d',
            'observacion' => 'PARA BUSCAR DATOS DE UN DNI',
            'activo' => 'SI'
        ]);

        DB::table('apis')->insert([
            'descripcion' => 'RUC',
            'host' => 'http://consulta.apiperu.pe',
            'url' => 'https://apiperu.dev/api/ruc',
            'token' => '54eb479a6c436dbefca61ea8e85e1884ced95a4591243fbfc3b7a4a79028ea3d',
            'observacion' => 'PARA BUSCAR DATOS DE UN RUC',
            'activo' => 'SI'
        ]);
    }
}
