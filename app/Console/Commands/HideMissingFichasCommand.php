<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HideMissingFichasCommand extends Command
{
    protected $signature = 'sync:hide-missing {--dry-run : Muestra cuántos productos se ocultarían sin hacerlo realmente}';
    
    protected $description = 'Oculta del catálogo (pagina_web = NO) los productos cuyo codigo_pc ya no es devuelto por el API de Peru Compras';

    const API_BASE = 'https://api-auditor.sekaitech.com.pe/api/v1';

    public function handle()
    {
        $this->info("Consultando API de fichas vigentes...");
        
        $codigosApi = [];
        
        // 1. Catalogo PC/Laptops
        $resp = Http::timeout(60)->get(self::API_BASE . '/fichas/catalog', [
            'marca' => 'KENYA TECHNOLOGY',
            'limit' => 2000,
        ]);
        
        if ($resp->successful()) {
            foreach ($resp->json()['items'] ?? [] as $item) {
                $codigo = strtoupper($item['nro_parte'] ?? '');
                if ($codigo) $codigosApi[] = $codigo;
            }
        } else {
            $this->error("Error al consultar el API (catalog). Status: " . $resp->status());
        }

        // 2. Catalogo Toneres
        $respToner = Http::timeout(60)->get(self::API_BASE . '/fichas/catalog', [
            'categoria' => 'TONER',
            'search'    => 'KENYA',
            'limit'     => 2000,
        ]);

        if ($respToner->successful()) {
            foreach ($respToner->json()['items'] ?? [] as $item) {
                $codigo = strtoupper($item['nro_parte'] ?? '');
                if ($codigo) $codigosApi[] = $codigo;
            }
        } else {
            $this->error("Error al consultar el API (toner). Status: " . $respToner->status());
        }

        if (empty($codigosApi)) {
            $this->error("El API no devolvió códigos válidos o hubo un error de conexión.");
            return 1;
        }
        $codigosApi = array_unique($codigosApi);
        $totalApi = count($codigosApi);
        
        $this->info("Fichas vigentes en el API: {$totalApi}");

        // Identificar modelos que son de Peru Compras (PCs/Laptops)
        $pcModelGroups = ['EZENT', 'PROWORK', 'OFISZU', 'RAITO', 'GENWORK'];
        $modeloIds = DB::table('modelos')
            ->where(function ($q) use ($pcModelGroups) {
                foreach ($pcModelGroups as $g) {
                    $q->orWhereRaw("UPPER(descripcion) LIKE ?", [$g . '%']);
                }
            })
            ->pluck('id')
            ->toArray();

        // Identificar categoría TONER
        $catToner = DB::table('categorias')->where('nombre', 'like', '%TONER%')->first();
        $tonerId = $catToner ? $catToner->id : -1;

        // Buscar productos huérfanos:
        // 1. Están visibles en la web
        // 2. Pertenecen a un modelo de Peru Compras o son Tóneres
        // 3. Su nro_parte y codigo_pc NO están en la lista del API
        $query = DB::table('productos')
            ->where('pagina_web', 'SI')
            ->where(function ($q) use ($modeloIds, $tonerId) {
                if (!empty($modeloIds)) {
                    $q->whereIn('modelo_id', $modeloIds);
                }
                if ($tonerId !== -1) {
                    $q->orWhere('categoria_id', $tonerId);
                }
            })
            ->where(function ($q) use ($codigosApi) {
                // nro_parte es NULO o NO ESTÁ en el API
                $q->where(function ($q2) use ($codigosApi) {
                    $q2->whereNull('nro_parte')
                       ->orWhereNotIn('nro_parte', $codigosApi);
                });
                // Y codigo_pc es NULO o NO ESTÁ en el API
                $q->where(function ($q3) use ($codigosApi) {
                    $q3->whereNull('codigo_pc')
                       ->orWhereNotIn('codigo_pc', $codigosApi);
                });
            });
            
        $count = $query->count();
        
        if ($count === 0) {
            $this->info("No hay productos huérfanos que deban ser ocultados.");
            return 0;
        }
        
        $this->warn("Se encontraron {$count} productos en la BD local con codigo_pc que ya no están en el API y que actualmente están visibles en la web.");
        
        if ($this->option('dry-run')) {
            $this->info("Modo DRY-RUN: No se realizó ningún cambio.");
            return 0;
        }
        
        $query->update(['pagina_web' => 'NO']);
        
        $this->info("¡Éxito! {$count} productos fueron ocultados del catálogo (pagina_web = 'NO').");
        
        return 0;
    }
}
