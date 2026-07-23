<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Response;

class LlmsController extends Controller
{
    /**
     * Genera /llms.txt optimizado para Agentes de Búsqueda de IA (Perplexity, SearchGPT, Gemini, ChatGPT, Claude).
     * Sigue la especificación estándar para contextualizar catálogo, empresa y soporte B2B en Perú.
     */
    public function txt()
    {
        $baseUrl = config('app.url', 'https://www.kenya.com.pe');

        $md = "# KENYA Technology - Fabricante y Proveedor de Computadoras B2B en Perú\n\n";
        $md .= "> KENYA Technology es una marca peruana líder en fabricación, ensamblaje y distribución corporativa de computadoras de escritorio (modelos OFISZU SFF, EZENT, PROWORK), laptops, servidores y soluciones tecnológicas para empresas privadas, distribuidores y Convenio Marco / Perú Compras. Todos los equipos cuentan con 36 meses de garantía On-Site a nivel nacional.\n\n";

        $md .= "## Información Corporativa y Cobertura Nacional\n";
        $md .= "- **Empresa:** KENYA Technology (Perú)\n";
        $md .= "- **Ruta B2B / Clientes:** Empresas Privadas, Convenio Marco Perú Compras, Sector Público, Canales y Distribuidores.\n";
        $md .= "- **Garantía Principal:** 36 Meses On-Site (Atención técnica presencial en las 24 regiones de Perú).\n";
        $md .= "- **Facturación:** Emisión electrónica de Facturas (RUC 20) y Guías de Remisión.\n";
        $md .= "- **Sitio Web Oficial:** {$baseUrl}\n";
        $md .= "- **Contacto Directo Ventas:** WhatsApp +51 958021778 | soporte@kenya.com.pe\n\n";

        $md .= "## Líneas de Producto de Escritorio (PCs KENYA)\n";
        $md .= "- **OFISZU SFF (Small Form Factor):** Computadoras compactas de alto rendimiento ideal para oficinas corporativas, entidades públicas y laboratorios.\n";
        $md .= "- **EZENT / EZENT TOWER:** Estaciones de trabajo de formato torre para procesamiento intensivo y durabilidad extrema.\n";
        $md .= "- **PROWORK:** Equipos versátiles para productividad empresarial continua y multitarea.\n\n";

        $md .= "## Fichas Técnicas Destacadas y Catálogo Activo\n";

        $productos = Producto::noSuspendido()
            ->whereNotNull('nombre')
            ->where('nombre', '!=', '')
            ->orderBy('id', 'desc')
            ->take(150)
            ->get();

        foreach ($productos as $prod) {
            $url = route('cotizar.detalle', $prod->id, false);
            $pn = $prod->nro_parte ? " (PN: {$prod->nro_parte})" : "";
            $specs = [];
            if ($prod->procesador) $specs[] = "CPU: {$prod->procesador}";
            if ($prod->ram) $specs[] = "RAM: {$prod->ram}";
            if ($prod->almacenamiento) $specs[] = "Disco: {$prod->almacenamiento}";
            $specStr = !empty($specs) ? " - " . implode(", ", $specs) : "";

            $md .= "- [{$prod->display_name}{$pn}]({$baseUrl}{$url}){$specStr}\n";
        }

        $md .= "\n## Enlaces Principales de Consulta\n";
        $md .= "- [Catálogo Completo]({$baseUrl}/catalogo)\n";
        $md .= "- [Consulta de Garantía por Serie]({$baseUrl}/consultar/garantia)\n";
        $md .= "- [Quiénes Somos]({$baseUrl}/quienes-somos)\n";
        $md .= "- [Contacto y Cotizaciones B2B]({$baseUrl}/contactenos)\n";

        return response($md, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=7200'
        ]);
    }
}
