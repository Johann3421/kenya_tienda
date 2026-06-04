#!/usr/bin/env python3
"""
enriquecer_endpoint.py
======================
Wrapper CLI que invoca la lógica de enriquecer_procesadores.py
y retorna SIEMPRE una respuesta JSON por stdout.

Es llamado por el controlador Laravel (EnriquecerProcesadoresController)
vía shell_exec / proc_open desde dentro del contenedor Dokploy.

Modos (primer argumento):
  status   → verifica conexión a BD y listado de variables de entorno
  test     → prueba los 5 procesadores fijos sin tocar la BD
  dry-run  → consulta N productos reales, muestra qué escribiría (sin UPDATE)
  run      → corre el enriquecimiento real con UPDATE en BD

Uso:
  python3 enriquecer_endpoint.py status
  python3 enriquecer_endpoint.py test
  python3 enriquecer_endpoint.py dry-run --limit 10
  python3 enriquecer_endpoint.py run --limit 50
"""

import sys
import json
import os
import time
import traceback
from pathlib import Path
from datetime import datetime

# Asegurar que el directorio del script esté en el path para importar el módulo hermano
BASE_DIR = Path(__file__).parent
sys.path.insert(0, str(BASE_DIR))

from dotenv import load_dotenv
load_dotenv(BASE_DIR / ".env")


# ─── Respuesta JSON helpers ───────────────────────────────────────────────────

def ok(data: dict):
    print(json.dumps({"ok": True, "timestamp": _now(), **data}, ensure_ascii=False, indent=2))
    sys.exit(0)

def error(msg: str, detail: str = ""):
    print(json.dumps({"ok": False, "error": msg, "detail": detail, "timestamp": _now()}, ensure_ascii=False, indent=2))
    sys.exit(1)

def _now():
    return datetime.now().strftime("%Y-%m-%d %H:%M:%S")


# ─── Modo: status ─────────────────────────────────────────────────────────────

def cmd_status():
    """Verifica la conexión a PostgreSQL y reporta variables de entorno relevantes."""
    try:
        import psycopg2
    except ImportError:
        error("psycopg2 no instalado", "pip install psycopg2-binary")

    host   = os.getenv("DB_HOST", "postgres-prod")
    port   = int(os.getenv("DB_PORT", "5432"))
    dbname = os.getenv("DB_DATABASE", "kenya_tienda")
    user   = os.getenv("DB_USERNAME", "kenya_app")
    pw     = os.getenv("DB_PASSWORD", "")

    try:
        conn = psycopg2.connect(host=host, port=port, dbname=dbname, user=user, password=pw)
        cur  = conn.cursor()
        cur.execute("SELECT COUNT(*) FROM productos WHERE procesador IS NOT NULL AND procesador <> ''")
        total_con_procesador = cur.fetchone()[0]
        cur.execute("SELECT COUNT(*) FROM productos WHERE procesador IS NOT NULL AND procesador <> '' AND (descripcion_2 IS NULL OR TRIM(descripcion_2) = '')")
        total_sin_descripcion2 = cur.fetchone()[0]
        cur.close()
        conn.close()

        ok({
            "db": "connected",
            "host": host,
            "port": port,
            "database": dbname,
            "user": user,
            "productos_con_procesador": total_con_procesador,
            "productos_sin_descripcion_2": total_sin_descripcion2,
            "anthropic_key_set": bool(os.getenv("ANTHROPIC_API_KEY")),
            "enrich_token_set": bool(os.getenv("ENRICH_TOKEN")),
        })
    except Exception as e:
        error("No se pudo conectar a PostgreSQL", str(e))


# ─── Modo: test ───────────────────────────────────────────────────────────────

def cmd_test():
    """Prueba los 5 procesadores fijos sin leer ni escribir la BD."""
    from enriquecer_procesadores import (
        enriquecer_procesador,
        PROCESADORES_TEST,
        cargar_progreso,
        guardar_progreso,
        cargar_fuentes,
        guardar_fuentes,
    )

    cache   = cargar_progreso()
    fuentes = cargar_fuentes()
    resultados = []

    for nombre in PROCESADORES_TEST:
        t0 = time.time()
        if nombre in cache:
            desc   = cache[nombre]
            fuente = fuentes.get(nombre, "cache")
        else:
            desc, fuente = enriquecer_procesador(nombre)
            if desc:
                cache[nombre]   = desc
                fuentes[nombre] = fuente
            time.sleep(0.5)

        resultados.append({
            "procesador":    nombre,
            "descripcion_2": desc,
            "fuente":        fuente,
            "ms":            round((time.time() - t0) * 1000),
        })

    guardar_progreso(cache)
    guardar_fuentes(fuentes)

    ok({"modo": "test", "resultados": resultados})


# ─── Modo: dry-run ────────────────────────────────────────────────────────────

def cmd_dry_run(limit: int = 10):
    """Consulta productos reales y muestra qué escribiría sin hacer UPDATE."""
    from enriquecer_procesadores import (
        enriquecer_procesador,
        conectar_db,
        obtener_productos,
        cargar_progreso,
        guardar_progreso,
        cargar_fuentes,
        guardar_fuentes,
    )

    conn    = conectar_db()
    cache   = cargar_progreso()
    fuentes = cargar_fuentes()

    productos = obtener_productos(conn, solo_vacios=True, limit=limit)
    conn.close()

    resultados = []
    for producto in productos:
        nombre = str(producto.get("procesador", "")).strip()
        if not nombre:
            continue

        if nombre in cache:
            desc   = cache[nombre]
            fuente = fuentes.get(nombre, "cache")
        else:
            desc, fuente = enriquecer_procesador(nombre)
            if desc:
                cache[nombre]   = desc
                fuentes[nombre] = fuente
            time.sleep(0.5)

        resultados.append({
            "id":            producto["id"],
            "procesador":    nombre,
            "descripcion_2": desc,
            "fuente":        fuente,
        })

    guardar_progreso(cache)
    guardar_fuentes(fuentes)

    ok({"modo": "dry-run", "limit": limit, "procesados": len(resultados), "resultados": resultados})


# ─── Modo: run ────────────────────────────────────────────────────────────────

def cmd_run(limit: int | None = None):
    """Enriquecimiento real: consulta productos y hace UPDATE en la BD."""
    from enriquecer_procesadores import (
        enriquecer_procesador,
        conectar_db,
        obtener_productos,
        actualizar_descripcion_2,
        cargar_progreso,
        guardar_progreso,
        cargar_fuentes,
        guardar_fuentes,
    )

    conn    = conectar_db()
    cache   = cargar_progreso()
    fuentes = cargar_fuentes()

    productos = obtener_productos(conn, solo_vacios=True, limit=limit)
    stats = {"nanoreview": 0, "intel_ark": 0, "claude": 0, "fallido": 0, "cache": 0}
    actualizados = 0

    try:
        for i, producto in enumerate(productos, 1):
            nombre = str(producto.get("procesador", "")).strip()
            if not nombre:
                continue

            if nombre in cache:
                desc   = cache[nombre]
                fuente = fuentes.get(nombre, "cache")
                stats["cache"] += 1
            else:
                desc, fuente = enriquecer_procesador(nombre)
                time.sleep(0.5)
                if desc:
                    cache[nombre]   = desc
                    fuentes[nombre] = fuente
                    stats[fuente]  += 1
                else:
                    stats["fallido"] += 1
                    continue

            if desc:
                actualizar_descripcion_2(conn, producto["id"], desc, dry_run=False)
                actualizados += 1

            if i % 50 == 0:
                conn.commit()
                guardar_progreso(cache)
                guardar_fuentes(fuentes)

        conn.commit()
    finally:
        guardar_progreso(cache)
        guardar_fuentes(fuentes)
        conn.close()

    ok({
        "modo": "run",
        "limit": limit,
        "total_productos": len(productos),
        "actualizados": actualizados,
        "stats": stats,
    })


# ─── CLI ─────────────────────────────────────────────────────────────────────

def parse_limit(argv: list[str]) -> int | None:
    for i, arg in enumerate(argv):
        if arg == "--limit" and i + 1 < len(argv):
            try:
                return int(argv[i + 1])
            except ValueError:
                pass
    return None


if __name__ == "__main__":
    try:
        args = sys.argv[1:]
        mode = args[0] if args else "status"

        if mode == "status":
            cmd_status()
        elif mode == "test":
            cmd_test()
        elif mode == "dry-run":
            cmd_dry_run(limit=parse_limit(args) or 10)
        elif mode == "run":
            cmd_run(limit=parse_limit(args))
        else:
            error(f"Modo desconocido: '{mode}'", "Modos válidos: status | test | dry-run | run")

    except SystemExit:
        raise
    except Exception as e:
        error("Excepción no controlada", traceback.format_exc())
