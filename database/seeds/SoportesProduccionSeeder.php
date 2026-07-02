<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoportesProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = <<<'SQL'
-- Script de migracion de Soportes para PostgreSQL

BEGIN;

DELETE FROM "soportes";

INSERT INTO "soportes" ("id", "codigo_barras", "numero_caso", "user_id", "cliente_id", "servicio", "estado", "equipo", "marca", "modelo", "serie", "nro_parte", "pieza_retirada", "pieza_serie", "pieza_falla", "piezas_retiradas_multiple", "piezas_adicionales_texto", "falla", "diagnostico", "descripcion", "accesorios", "acuenta", "costo_servicio", "saldo_total", "fecha_registro", "fecha_entrega", "observacion", "reporte_tecnico", "pdf_link", "confirmar_reparacion", "solo_diagnostico", "activo", "facturado", "created_at", "updated_at") VALUES

SELECT setval('soportes_id_seq', COALESCE((SELECT MAX(id)+1 FROM "soportes"), 1), false);

COMMIT;

SQL;
        DB::unprepared($sql);
        $this->command->info('Datos de soportes insertados exitosamente en PostgreSQL.');
    }
}
