<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Controlador de vistas informativas para normas internacionales ISO de KENYA Technology.
 */
class NormaIsoController extends Controller
{
    /**
     * Muestra la pagina de Norma Internacional ISO 9001:2015.
     */
    public function iso9001(): View
    {
        return $this->renderNorma('9001');
    }

    /**
     * Muestra la pagina de Norma Internacional ISO 14001:2015.
     */
    public function iso14001(): View
    {
        return $this->renderNorma('14001');
    }

    /**
     * Resuelve los datos de la norma y renderiza la plantilla compartida.
     */
    private function renderNorma(string $codigo): View
    {
        $normas = self::catalogoNormas();
        abort_unless(isset($normas[$codigo]), 404);

        return view('norma-iso', [
            'norma' => $normas[$codigo],
        ]);
    }

    /**
     * Catalogo centralizado de normas internacionales publicadas en Nuestra Empresa.
     */
    private static function catalogoNormas(): array
    {
        return [
            '9001' => [
                'code' => 'ISO 9001:2015',
                'route_name' => 'iso.9001',
                'seo_title' => 'Norma Internacional ISO 9001:2015 · Gestión de Calidad | KENYA Technology',
                'meta_description' => 'Sistema de Gestión de Calidad ISO 9001:2015 de KENYA Technology. Estándares internacionales orientados a la satisfacción de nuestros clientes, eficiencia de procesos y mejora continua.',
                'meta_keywords' => 'norma iso 9001 2015 kenya, sistema gestion de calidad kenya technology, calidad computadoras peru, mejora continua',
                'og_title' => 'Norma Internacional ISO 9001:2015 | KENYA Technology Perú',
                'og_description' => 'Conoce los lineamientos del Sistema de Gestión de Calidad ISO 9001:2015 de KENYA Technology.',
                'hero_title' => 'Norma ISO 9001:2015',
                'hero_subtitle' => 'Sistema de Gestión de Calidad y Mejora Continua Empresarial',
                'breadcrumb' => 'Norma ISO 9001:2015',
                'kicker' => 'Nuestra Empresa · Norma Internacional ISO 9001:2015',
                'heading' => 'Sistema de Gestión de Calidad ISO 9001:2015',
                'lead' => 'En KENYA TECHNOLOGY trabajamos bajo estándares internacionales de gestión de calidad, orientados a la satisfacción de nuestros clientes, la eficiencia de nuestros procesos y la mejora continua.',
                'meta_label' => 'Norma Internacional',
                'lineamientos_label' => 'Lineamientos',
                'lineamientos_count' => '5 pilares',
                'items' => [
                    [
                        'titulo' => 'Compromiso con la calidad:',
                        'detalle' => 'nuestra política y objetivos.',
                    ],
                    [
                        'titulo' => 'Alcance del sistema de gestión:',
                        'detalle' => 'procesos, productos y servicios comprendidos.',
                    ],
                    [
                        'titulo' => 'Enfoque al cliente:',
                        'detalle' => 'atención, cumplimiento y satisfacción.',
                    ],
                    [
                        'titulo' => 'Control de calidad:',
                        'detalle' => 'evaluación de proveedores, productos y procesos.',
                    ],
                    [
                        'titulo' => 'Mejora continua:',
                        'detalle' => 'seguimiento de indicadores y acciones correctivas.',
                    ],
                ],
                'image' => 'iso-9001.jpg',
                'image_alt' => 'Sistema de Gestión de Calidad ISO 9001:2015 de KENYA TECHNOLOGY',
                'card_title' => 'Norma ISO 9001:2015',
                'card_subtitle' => 'Sistema de Gestión de Calidad',
                'contact_title' => 'Calidad y Atención al Cliente',
                'contact_desc' => 'Canal directo para consultas sobre nuestros estándares de calidad, procesos y productos.',
                'contact_cta_label' => 'Contactar con Calidad',
                'contact_cta_route' => 'contactenos',
            ],
            '14001' => [
                'code' => 'ISO 14001:2015',
                'route_name' => 'iso.14001',
                'seo_title' => 'Norma Internacional ISO 14001:2015 · Gestión Ambiental | KENYA Technology',
                'meta_description' => 'Sistema de Gestión Ambiental ISO 14001:2015 de KENYA Technology. Gestión ambiental responsable, uso eficiente de recursos, gestión de residuos RAEE y mejora continua.',
                'meta_keywords' => 'norma iso 14001 2015 kenya, sistema gestion ambiental kenya technology, raee peru, sostenibilidad empresarial',
                'og_title' => 'Norma Internacional ISO 14001:2015 | KENYA Technology Perú',
                'og_description' => 'Conoce los lineamientos del Sistema de Gestión Ambiental ISO 14001:2015 de KENYA Technology.',
                'hero_title' => 'Norma ISO 14001:2015',
                'hero_subtitle' => 'Sistema de Gestión Ambiental y Sostenibilidad Empresarial',
                'breadcrumb' => 'Norma ISO 14001:2015',
                'kicker' => 'Nuestra Empresa · Norma Internacional ISO 14001:2015',
                'heading' => 'Sistema de Gestión Ambiental ISO 14001:2015',
                'lead' => 'En KENYA TECHNOLOGY promovemos una gestión ambiental responsable, orientada al uso eficiente de los recursos, la reducción de residuos tecnológicos y la mejora continua de nuestros procesos, contribuyendo a un futuro más sostenible.',
                'meta_label' => 'Norma Internacional',
                'lineamientos_label' => 'Lineamientos',
                'lineamientos_count' => '6 lineamientos',
                'items' => [
                    [
                        'titulo' => 'Política ambiental.',
                        'detalle' => null,
                    ],
                    [
                        'titulo' => 'Objetivos ambientales.',
                        'detalle' => null,
                    ],
                    [
                        'titulo' => 'Gestión de residuos electrónicos (RAEE).',
                        'detalle' => null,
                    ],
                    [
                        'titulo' => 'Uso eficiente de energía y recursos.',
                        'detalle' => null,
                    ],
                    [
                        'titulo' => 'Cumplimiento de obligaciones ambientales.',
                        'detalle' => null,
                    ],
                    [
                        'titulo' => 'Sistema de Gestión Ambiental ISO 14001:2015 y certificación.',
                        'detalle' => null,
                    ],
                ],
                'image' => 'iso-14001.jpg',
                'image_alt' => 'Sistema de Gestión Ambiental ISO 14001:2015 de KENYA TECHNOLOGY',
                'card_title' => 'Norma ISO 14001:2015',
                'card_subtitle' => 'Sistema de Gestión Ambiental',
                'contact_title' => 'Gestión Ambiental y RAEE',
                'contact_desc' => 'Conoce nuestro programa gratuito de reciclaje tecnológico y atención para entidades y empresas.',
                'contact_cta_label' => 'Ver programa de Reciclaje',
                'contact_cta_route' => 'reciclaje',
            ],
        ];
    }
}
