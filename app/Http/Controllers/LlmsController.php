<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Models\Categoria;
use Illuminate\Http\Response;

class LlmsController extends Controller
{
    /**
     * Genera /llms.txt optimizado para Agentes de Búsqueda de IA (Perplexity, SearchGPT, Gemini, ChatGPT, Claude).
     * Incluye términos generales (Computadoras, PCs, Laptops) y específicos (B2B, Convenio Marco, Fichas Técnicas).
     */
    public function txt()
    {
        $baseUrl = config('app.url', 'https://www.kenya.com.pe');

        $md = "# KENYA Technology - Computadoras, PCs de Escritorio, Laptops y Equipos de Cómputo en Perú\n\n";
        $md .= "> KENYA Technology es una marca peruana líder en venta de computadoras, PCs de escritorio, laptops, monitores, tóner y equipos de cómputo para el hogar, oficinas, empresas, distribuidores y sector público / Convenio Marco en todo el Perú. Todos los equipos incluyen 36 meses de garantía On-Site a nivel nacional.\n\n";

        $md .= "## Términos Principales de Búsqueda y Productos\n";
        $md .= "- **Computadoras y PCs de Escritorio:** Modelos OFISZU SFF, EZENT, PROWORK, HENKO en formatos Slim, SFF y Torre.\n";
        $md .= "- **Laptops y Computadoras Portátiles:** Equipos portátiles para trabajo, oficina, estudio y empresas.\n";
        $md .= "- **Monitores:** Pantallas y monitores LED/FHD para oficina y hogar.\n";
        $md .= "- **Tóner y Suministros:** Cartuchos de tóner originales e insumos de impresión.\n";
        $md .= "- **Garantía y Servicio:** 36 Meses de Garantía On-Site con atención técnica en las 24 regiones de Perú.\n\n";

        $md .= "## Información de Venta y Cobertura Nacional (Perú)\n";
        $md .= "- **Venta:** Venta directa al por mayor y menor, cotizaciones corporativas B2B y Convenio Marco / Perú Compras.\n";
        $md .= "- **Comprobantes:** Facturación Electrónica (RUC 20 / DNI) y Guías de Remisión.\n";
        $md .= "- **Atención y Cotizaciones:** WhatsApp +51 958021778 | soporte@kenya.com.pe\n";
        $md .= "- **Sitio Web:** {$baseUrl}\n\n";

        $md .= "## Catálogo de Computadoras y Fichas Técnicas por Categoría\n\n";

        $productos = Producto::noSuspendido()
            ->with('getCategoria')
            ->whereNotNull('nombre')
            ->where('nombre', '!=', '')
            ->orderBy('id', 'desc')
            ->get();

        $grouped = $productos->groupBy(function($item) {
            $catName = mb_strtoupper($item->getCategoria->nombre ?? 'OTRAS COMPUTADORAS Y EQUIPOS', 'UTF-8');
            if (str_contains($catName, 'COMPUTADORA') || str_contains($catName, 'PC') || str_contains($catName, 'DESKTOP')) {
                return 'Computadoras de Escritorio (PCs)';
            } elseif (str_contains($catName, 'LAPTOP') || str_contains($catName, 'PORTATIL')) {
                return 'Laptops y Portátiles';
            } elseif (str_contains($catName, 'MONITOR') || str_contains($catName, 'PANTALLA')) {
                return 'Monitores y Pantallas';
            } elseif (str_contains($catName, 'TONER') || str_contains($catName, 'SUMINISTRO')) {
                return 'Tóner e Insumos de Impresión';
            }
            return 'Otros Equipos de Cómputo';
        });

        foreach ($grouped as $categoryName => $prods) {
            $md .= "### {$categoryName}\n";
            foreach ($prods->take(80) as $prod) {
                $url = route('cotizar.detalle', $prod->id, false);
                $pn = $prod->nro_parte ? " (PN: {$prod->nro_parte})" : "";
                $specs = [];
                if ($prod->procesador) $specs[] = "CPU: {$prod->procesador}";
                if ($prod->ram) $specs[] = "RAM: {$prod->ram}";
                if ($prod->almacenamiento) $specs[] = "Disco: {$prod->almacenamiento}";
                $specStr = !empty($specs) ? " - " . implode(", ", $specs) : "";

                $md .= "- [Computadora / PC: {$prod->display_name}{$pn}]({$baseUrl}{$url}){$specStr}\n";
            }
            $md .= "\n";
        }

        $md .= "## Enlaces Principales\n";
        $md .= "- [Catálogo General de Computadoras y PCs]({$baseUrl}/catalogo)\n";
        $md .= "- [Consulta de Garantía por Serie]({$baseUrl}/consultar/garantia)\n";
        $md .= "- [Contacto y Ventas Directas]({$baseUrl}/contactenos)\n";

        return response($md, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=7200'
        ]);
    }
}
