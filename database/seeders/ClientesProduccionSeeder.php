<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesProduccionSeeder extends Seeder
{
    public function run()
    {
        $sql = <<<'SQL'
-- Script de migracion de clientes para PostgreSQL

BEGIN;

INSERT INTO "clientes" ("id", "tipo", "codigo_sunat", "nombres", "direccion", "email", "celular", "user_id", "created_at", "updated_at") VALUES
(20131365994, 'RUC', '6', 'INSTITUTO NACIONAL DE INNOVACION AGRARIA', 'AV. LAMOLINAN° 1981 - LAMOLINA-LIMA', 'SOPORTE_03@INIA.GOB.PE', '923238169', 9, '2026-02-18 20:34:35', '2026-02-18 21:20:56'),
(20159855938, 'RUC', '6', 'AESALUD HOSPITAL VICTOR LARCO HERRERA', 'AV. DEL EJERCITO NRO. S/N (CUADRA 06 LADO IMPAR)', 'SOPORTE@HVLH.GO.PE', '973810864', 9, '2026-01-13 15:12:19', '2026-01-22 16:01:56'),
(20166550239, 'RUC', '6', 'FRANK SAENZ LLIUYA', 'JIRON CIRO ALEGRIA S/N SOLEDAD ALTO', 'FSANEZL@UNASAM.EDU.PE', '933628192', 8, '2026-04-08 21:54:45', '2026-04-09 14:11:22'),
(20169004359, 'RUC', '6', 'UNIVERSIDAD NACIONAL DE INGENIERIA', 'AV. TUPAC AMARU NRO. 210 (KM. 4.5 TUPAC AMARU)', 'EPINDUSTRIALES@UNI.EDU.PE', '953272133', 1, '2026-01-22 21:48:45', '2026-02-01 00:22:02'),
(20291973851, 'RUC', '6', 'OFICINA NACIONAL DE PROCESOS ELECTORALES', 'JR. WASHINGTON 1894 LIMA - LIMA - LIMA', 'G.SGIST.JCDN@OUTLOOK.COM', '995126531', 9, '2025-05-13 14:44:45', '2025-09-30 16:35:58'),
(20489498783, 'RUC', '6', 'RED DE SALUD HUANUCO', ': AV.CARRETERACENTRAL N° 266 LLICUA - HUANUCO', 'YESSITA22803@HOTMAIL.COM', '992216383', 5, '2025-06-10 14:58:52', '2025-09-06 00:30:24'),
(20527141762, 'RUC', '6', 'GOBIERNO REGIONAL DE APURIMAC', 'IE INICIAL N°1105 SANTA ISABEL', 'NULL', '123456789', 9, '2026-02-05 20:42:42', '2026-02-05 20:42:42') ON CONFLICT ("id") DO NOTHING;


COMMIT;

SQL;
        DB::unprepared($sql);
        $this->command->info('Datos de clientes insertados exitosamente en PostgreSQL (ignorando duplicados).');
    }
}
