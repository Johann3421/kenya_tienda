<?php

namespace App\Http\Controllers;

use App\Modelo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Genera un Sitemap XML dinámico optimizado para Google Search Console e indexación de motores de búsqueda.
     */
    public function index()
    {
        $host = request()->getHost();
        $configuredUrl = (string) config('app.url', '');

        // Forzar https://www.kenya.com.pe si la petición o el entorno apuntan a kenya o al dominio staging de sekaitech
        if (str_contains($host, 'kenya.com.pe') || empty($host) || str_contains($configuredUrl, 'sekaitech') || str_contains($configuredUrl, 'kenya.com.pe')) {
            $baseUrl = 'https://www.kenya.com.pe';
        } else {
            $baseUrl = rtrim(request()->getSchemeAndHttpHost() ?: ($configuredUrl ?: 'https://www.kenya.com.pe'), '/');
        }

        $cacheKey = 'sitemap_xml_' . md5($baseUrl);

        $xml = Cache::remember($cacheKey, 1800, function () use ($baseUrl) {
            // Páginas estáticas principales
            $staticUrls = [
                ['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'daily'],
                ['loc' => $baseUrl . '/catalogo', 'priority' => '0.9', 'changefreq' => 'daily'],
                ['loc' => $baseUrl . '/quienes-somos', 'priority' => '0.6', 'changefreq' => 'monthly'],
                ['loc' => $baseUrl . '/novedades', 'priority' => '0.7', 'changefreq' => 'weekly'],
                ['loc' => $baseUrl . '/consultar/garantia', 'priority' => '0.7', 'changefreq' => 'weekly'],
                ['loc' => $baseUrl . '/contactenos', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ];

            // Modelos de catálogo activos
            $modelos = Modelo::whereRaw("UPPER(activo) = 'SI'")
                ->whereHas('getProducto', function ($q) {
                    $q->where('pagina_web', 'SI')->noSuspendido();
                })
                ->get(['id', 'updated_at']);

            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            foreach ($staticUrls as $url) {
                $content .= '<url>';
                $content .= '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
                $content .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
                $content .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
                $content .= '<priority>' . $url['priority'] . '</priority>';
                $content .= '</url>';
            }

            foreach ($modelos as $modelo) {
                $lastmod = $modelo->updated_at ? $modelo->updated_at->format('Y-m-d') : date('Y-m-d');
                $modUrl = '/catalogo/' . $modelo->id . '/detallemod';

                $content .= '<url>';
                $content .= '<loc>' . htmlspecialchars($baseUrl . $modUrl) . '</loc>';
                $content .= '<lastmod>' . $lastmod . '</lastmod>';
                $content .= '<changefreq>weekly</changefreq>';
                $content .= '<priority>0.85</priority>';
                $content .= '</url>';
            }

            $content .= '</urlset>';

            return $content;
        });

        return response($xml, 200, [
            'Content-Type' => 'text/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=1800'
        ]);
    }
}
