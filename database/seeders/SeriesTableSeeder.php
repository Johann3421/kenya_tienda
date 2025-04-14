<?php

namespace Database\Seeders;

use App\Serie;
use Illuminate\Database\Seeder;

class SeriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Serie::firstOrCreate([
            'serie' => 'B001',
            'tipo' => 'B',
            'descripcion' => 'BOLETA'
        ]);
        Serie::firstOrCreate([
            'serie' => 'F001',
            'tipo' => 'F',
            'descripcion' => 'FACTURA'
        ]);
        Serie::firstOrCreate([
            'serie' => 'F002',
            'tipo' => 'F',
            'descripcion' => 'FACTURA'
        ]);
        Serie::firstOrCreate([
            'serie' => 'F003',
            'tipo' => 'F',
            'descripcion' => 'FACTURA'
        ]);
        Serie::firstOrCreate([
            'serie' => '0001',
            'tipo' => 'CI',
            'descripcion' => 'OTROS'
        ]);
    }
}
