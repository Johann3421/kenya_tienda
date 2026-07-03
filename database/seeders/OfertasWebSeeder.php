<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfertasWebSeeder extends Seeder
{
    public function run()
    {
        DB::table('ofertas_web')->insert([
            [
                'titulo'      => 'OFISZU SFF',
                'descripcion' => 'Ofreciendo equipos ultra compactos, elegantes y eficientes.',
                'imagen'      => 'ofiszusff.png',
                'color_fondo' => 'bg-purple',
                'activo'      => 'SI',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titulo'      => 'Grandes descuentos',
                'descripcion' => 'Es tu oportunidad para renovar o adquirir tu equipo Kenya',
                'imagen'      => 'descuentos.png',
                'color_fondo' => 'bg-dark-purple',
                'activo'      => 'SI',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titulo'      => 'EZENT V1_MT',
                'descripcion' => 'Equipo diseñado especialmente para usuarios de oficina y empresas',
                'imagen'      => 'cuabanner.png',
                'color_fondo' => 'bg-red',
                'activo'      => 'SI',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
