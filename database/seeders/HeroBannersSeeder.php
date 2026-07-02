<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;

class HeroBannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banners')->truncate();

        $banners = [
            [
                'imagen' => 'BANNERS/banner-mundial.png',
                'titulo' => 'OFISZU SFF',
                'descripcion' => 'COMPUTADORA DE ESCRITORIO',
                'contenido' => 'Diseñada para oficinas modernas y espacios reducidos, ofreciendo equipos ultra compactos, elegantes y eficientes para todo tipo de usuarios.',
                'link' => '/catalogo',
                'link_nombre' => 'Ver Catálogo',
                'activo' => 'SI',
            ],
            [
                'imagen' => 'BANNERS/banner-mundial1.png',
                'titulo' => 'GENWORK',
                'descripcion' => 'COMPUTADORA DE ESCRITORIO',
                'contenido' => 'Diseñada para usuarios de oficina, profesionales y diseñadores que requieren mayor rendimiento gráfico y estabilidad para el trabajo diario.',
                'link' => '/catalogo',
                'link_nombre' => 'Ver Catálogo',
                'activo' => 'SI',
            ],
            [
                'imagen' => 'BANNERS/banner-mundial3.png',
                'titulo' => 'PROWORK',
                'descripcion' => 'ESTACIÓN DE TRABAJO',
                'contenido' => 'Diseñada especialmente para trabajo continuo y entornos profesionales exigentes, adaptándose a todo tipo de usuario y aplicaciones especializadas.',
                'link' => '/catalogo',
                'link_nombre' => 'Ver Catálogo',
                'activo' => 'SI',
            ],
            [
                'imagen' => 'BANNERS/banner-mundial2.png',
                'titulo' => 'EZENT',
                'descripcion' => 'COMPUTADORA DE ESCRITORIO',
                'contenido' => 'Diseñada especialmente para usuarios de oficina y empresas, ofreciendo múltiples configuraciones adaptadas a cada necesidad de trabajo.',
                'link' => '/catalogo',
                'link_nombre' => 'Ver Catálogo',
                'activo' => 'SI',
            ]
        ];

        foreach ($banners as $banner) {
            Banner::insert($banner);
        }
    }
}
