<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetallesSoportesProduccionSeeder extends Seeder
{
    public function run()
    {
        $sql = <<<'SQL'
-- Script de migracion de detalles_soportes para PostgreSQL

BEGIN;

INSERT INTO "detalles_soportes" ("id", "soporte_id", "descripcion", "precio", "descuento", "cantidad", "importe", "created_at", "updated_at") VALUES
(15, 16, 'CAMBIO DE PLACA MADRE - H610', 666.00, 's4m0ph39w85544l', 1, 666.00, '2025-06-10 14:56:27', '2025-06-10 14:56:27'),
(16, 17, 'CAMBIO DE PLACA MADRE - H610', 666.00, 's4m0ph31h648rfz', 1, 666.00, '2025-06-10 15:07:58', '2025-06-10 15:07:58'),
(17, 18, 'CAMBIO MBASH610M-K D4', 0.00, 'S5MOPH399830ARP', 1, 0.00, '2025-06-30 16:09:57', '2025-06-30 16:09:57'),
(18, 19, 'CAMBIO MBASH610M-K D4', 0.00, 'S5MOPH399830ARP', 1, 0.00, '2025-06-30 16:13:23', '2025-06-30 16:13:23'),
(19, 22, 'CAMBIO DE PLACA', 800.00, 'S4M0PH3245293CH', 1, 800.00, '2026-01-13 15:12:20', '2026-01-13 15:12:20'),
(20, 22, 'PROCESADOR ', 800.00, 'LGA 1700', 1, 800.00, '2026-01-13 15:12:20', '2026-01-13 15:12:20'),
(22, 24, 'CAMBIO DE FUENTE-G850 80 PLUS GOLD ', 260.00, 'R149900', 1, 260.00, '2026-01-22 21:48:46', '2026-01-22 21:48:46') ON CONFLICT ("id") DO NOTHING;


COMMIT;

SQL;
        DB::unprepared($sql);
        $this->command->info('Datos de detalles_soportes insertados exitosamente en PostgreSQL (ignorando duplicados).');
    }
}
