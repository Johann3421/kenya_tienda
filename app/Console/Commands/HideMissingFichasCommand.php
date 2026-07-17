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

        // ponytail: Update where codigo_pc is not null, not in API, and currently pagina_web = 'SI'
        $query = DB::table('productos')
            ->whereNotNull('codigo_pc')
            ->where('codigo_pc', '!=', '')
            ->whereNotIn('codigo_pc', $codigosApi)
            ->where('pagina_web', 'SI');
            
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
