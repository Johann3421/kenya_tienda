<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Models\Categoria;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Genera un Sitemap XML dinámico optimizado para Google Search Console e indexación de motores de búsqueda.
     */
    public function index()
    {
        $baseUrl = config('app.url', 'https://www.kenya.com.pe');

        // Páginas estáticas principales
        $staticUrls = [
            ['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/catalogo', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/quienes-somos', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/novedades', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl . '/consultar/garantia', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl . '/contactenos', 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        // Productos activos indexables
        $productos = Producto::noSuspendido()
            ->whereNotNull('nombre')
            ->where('nombre', '!=', '')
            ->orderBy('id', 'desc')
            ->get(['id', 'updated_at']);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($staticUrls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
            $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
            $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $url['priority'] . '</priority>';
            $xml .= '</url>';
        }

        foreach ($productos as $producto) {
            $lastmod = $producto->updated_at ? $producto->updated_at->format('Y-m-d') : date('Y-m-d');
            $prodUrl = route('cotizar.detalle', $producto->id, false);

            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($baseUrl . $prodUrl) . '</loc>';
            $xml .= '<lastmod>' . $lastmod . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'text/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }
}
