<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Producto;
use App\Models\Marca;

class SyncKenyaProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kenya:sync-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza el catálogo de productos de Kenya desde la API externa (Perú Compras).';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando sincronización de productos...');
        Log::info('SyncKenyaProducts: Iniciando sincronización.');

        try {
            $response = Http::timeout(30)->get('https://api-auditor.sekaitech.com.pe/api/v1/fichas/export/kenya');

            if ($response->failed()) {
                $this->error('Error al conectar con la API.');
                Log::error('SyncKenyaProducts: Fallo de conexión a la API. Status: ' . $response->status());
                return 1;
            }

            $data = $response->json();
            
            if (!isset($data['status']) || $data['status'] !== 'ok' || !isset($data['data'])) {
                $this->error('Formato de respuesta inesperado.');
                Log::error('SyncKenyaProducts: Formato JSON no válido o status no es ok.');
                return 1;
            }

            $productosApi = $data['data'];
            $skusApi = [];
            $creados = 0;
            $actualizados = 0;

            // Buscar el ID de la marca KENYA
            $marcaKenya = Marca::where('nombre', 'like', '%KENYA%')->first();
            $marcaId = $marcaKenya ? $marcaKenya->id : null;

            foreach ($productosApi as $item) {
                $sku = $item['sku'] ?? null;
                if (!$sku) continue;

                $skusApi[] = $sku;
                
                // Buscar por SKU o nro_parte
                $producto = Producto::where('sku', $sku)->orWhere('nro_parte', $sku)->first();

                if ($producto) {
                    // Si existe, actualizamos
                    $producto->nombre = $item['nombre'];
                    $producto->pdf_link = $item['pdf_link'] ?? null;
                    $producto->activo = 'SI';
                    $producto->sku = $sku; // Garantizamos que se asigne al nuevo campo sku
                    
                    // Si la API nos envía una imagen y el producto no tiene una, podríamos asignarla
                    // pero según el prompt la API envía imagen vacía, dejamos la actual.
                    
                    $producto->save();
                    $actualizados++;
                } else {
                    // Si NO existe, lo creamos
                    $producto = new Producto();
                    $producto->sku = $sku;
                    $producto->nro_parte = $sku; // Fallback al campo estandar
                    $producto->nombre = $item['nombre'];
                    $producto->descripcion = $item['caracteristicas'] ?? $item['nombre'];
                    $producto->pdf_link = $item['pdf_link'] ?? null;
                    $producto->activo = 'SI';
                    $producto->marca_id = $marcaId;
                    
                    // Imagen por defecto (placeholder)
                    $producto->imagen = 'default.png';
                    
                    // Valores por defecto para campos no nulos requeridos por Laravel (opcional pero recomendado)
                    $producto->tipo_afectacion = '10'; // Gravado
                    $producto->save();
                    
                    $creados++;
                }
            }

            // Ocultar productos de la marca "Kenya" que NO vinieron en el JSON
            $queryInactivar = Producto::where('activo', 'SI');
            if ($marcaId) {
                $queryInactivar->where('marca_id', $marcaId);
            } else {
                $queryInactivar->where('nombre', 'like', '%KENYA%');
            }
            
            $productosActivos = $queryInactivar->get();
            $inactivados = 0;

            foreach ($productosActivos as $prod) {
                $prodSku = $prod->sku ?: $prod->nro_parte;
                
                if (!in_array($prodSku, $skusApi)) {
                    // Ya no está OFERTADA
                    $prod->activo = 'NO';
                    $prod->save();
                    $inactivados++;
                }
            }

            $this->info("Sincronización completada. Creados: $creados, Actualizados: $actualizados, Inactivados: $inactivados");
            Log::info("SyncKenyaProducts: Sincronización Exitosa. Creados: $creados, Actualizados: $actualizados, Inactivados: $inactivados");

            return 0;

        } catch (\Exception $e) {
            $this->error('Excepción: ' . $e->getMessage());
            Log::error('SyncKenyaProducts: Excepción capturada. ' . $e->getMessage());
            return 1;
        }
    }
}
