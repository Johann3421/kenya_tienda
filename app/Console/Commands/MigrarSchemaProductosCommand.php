<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Producto;
use App\Models\Especificacion;
use Illuminate\Support\Facades\DB;

class MigrarSchemaProductosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrar-schema-productos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra datos de la tabla productos y especificaciones al nuevo esquema normalizado (producto_especificaciones, producto_precios, producto_imagenes y especificaciones_json)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando migración y normalización de la Base de Datos de Productos...');

        $productos = Producto::all();
        $total = $productos->count();
        $this->info("Total de productos a procesar: {$total}");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($productos as $producto) {
            DB::transaction(function () use ($producto) {
                // 1. Migrar Precios
                $precioRef = $producto->precio_referencial ?? $producto->precio_especial ?? $producto->precio_unitario;
                if (!empty($precioRef) && is_numeric($precioRef) && $precioRef > 0) {
                    DB::table('producto_precios')->updateOrInsert(
                        ['producto_id' => $producto->id, 'tipo_cliente' => 'regular'],
                        [
                            'moneda' => 'USD',
                            'precio' => $precioRef,
                            'incluye_igv' => false,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }

                if (!empty($producto->precio_especial) && is_numeric($producto->precio_especial) && $producto->precio_especial > 0) {
                    DB::table('producto_precios')->updateOrInsert(
                        ['producto_id' => $producto->id, 'tipo_cliente' => 'canal'],
                        [
                            'moneda' => 'USD',
                            'precio' => $producto->precio_especial,
                            'incluye_igv' => false,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }

                // 2. Migrar Imágenes (1-a-5)
                for ($i = 1; $i <= 5; $i++) {
                    $imgAttr = "imagen_{$i}";
                    if (!empty($producto->{$imgAttr})) {
                        DB::table('producto_imagenes')->updateOrInsert(
                            ['producto_id' => $producto->id, 'url' => $producto->{$imgAttr}],
                            [
                                'orden' => $i,
                                'es_principal' => ($i === 1),
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );
                    }
                }

                // 3. Consolidar Especificaciones de EAV + Monolíticas
                $specsMap = [];

                // Cargar especificaciones existentes de la tabla especificaciones (EAV)
                $dbSpecs = Especificacion::where('producto_id', $producto->id)->get();
                foreach ($dbSpecs as $sp) {
                    if (empty(trim($sp->campo)) || empty(trim($sp->descripcion))) continue;
                    $clave = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $sp->campo)));
                    $specsMap[$clave] = [
                        'etiqueta' => trim($sp->campo),
                        'valor' => trim($sp->descripcion),
                    ];
                }

                // Map de columnas legacy directas en productos
                $legacyCols = [
                    'procesador' => 'Procesador',
                    'ram' => 'Memoria RAM',
                    'almacenamiento' => 'Almacenamiento',
                    'tarjetavideo' => 'Tarjeta de Video',
                    'resolucion' => 'Resolución',
                    'sistema_operativo' => 'Sistema Operativo',
                    'teclado' => 'Teclado',
                    'mouse' => 'Mouse',
                    'suite_ofimatica' => 'Suite Ofimática',
                    'garantia_de_fabrica' => 'Garantía de Fábrica',
                    'empaque_de_fabrica' => 'Empaque de Fábrica',
                    'certificacion' => 'Certificaciones',
                    'unidad_optica' => 'Unidad Óptica',
                    'conectividad' => 'LAN / Red',
                    'conectividad_wlan' => 'WLAN / Wi-Fi',
                    'conectividad_usb' => 'Puertos USB',
                    'chipset' => 'Chipset',
                    'fuente_poder' => 'Fuente de Poder',
                ];

                foreach ($legacyCols as $col => $label) {
                    if (!empty($producto->{$col})) {
                        $val = trim($producto->{$col});
                        if (empty($val) || in_array(strtoupper($val), ['NULL', 'N/A', '-'])) continue;

                        $clave = $col;
                        if (!isset($specsMap[$clave])) {
                            $specsMap[$clave] = [
                                'etiqueta' => $label,
                                'valor' => $val,
                            ];
                        }
                    }
                }

                // Insertar en producto_especificaciones y preparar JSONB
                $jsonArray = [];
                $orden = 1;
                foreach ($specsMap as $clave => $data) {
                    DB::table('producto_especificaciones')->updateOrInsert(
                        ['producto_id' => $producto->id, 'clave' => $clave],
                        [
                            'etiqueta' => $data['etiqueta'],
                            'valor' => $data['valor'],
                            'orden' => $orden++,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );

                    $jsonArray[$clave] = $data['valor'];
                }

                // Guardar JSONB consolidado en la tabla productos
                if (!empty($jsonArray)) {
                    $producto->especificaciones_json = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
                    $producto->save();
                }
            });

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('¡Migración completada exitosamente! Todas las especificaciones, imágenes y precios han sido normalizados.');

        return 0;
    }
}
