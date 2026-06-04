#!/usr/bin/env python3
"""
enriquecer_procesadores.py
==========================
Rellena el campo `descripcion_2` de la tabla `productos` con las specs
técnicas del procesador, usando tres fuentes en cascada:

  1. Nanoreview API  → https://nanoreview.net/api/cpu/get?slug={slug}
  2. Intel ARK API   → solo para procesadores Intel
  3. Claude (fallback final)

Uso:
    python enriquecer_procesadores.py                  # procesa todos
    python enriquecer_procesadores.py --dry-run        # solo muestra, no escribe en BD
    python enriquecer_procesadores.py --test           # prueba solo los 5 procesadores de ejemplo
    python enriquecer_procesadores.py --limit 20       # limita a N productos
    python enriquecer_procesadores.py --solo-vacios    # solo productos sin descripcion_2

Requiere:
    pip install requests anthropic psycopg2-binary python-dotenv
"""

import os
import re
import sys
import json
import time
import logging
import argparse
from datetime import datetime
from pathlib import Path

import requests
from dotenv import load_dotenv

# ─── Cargar variables de entorno desde .env ────────────────────────────────────
load_dotenv(Path(__file__).parent / ".env")

# ─── Configuración de logging ──────────────────────────────────────────────────
LOG_FILE = Path("/tmp/enriquecer_procesadores.log")

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s  %(levelname)-8s  %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S",
    handlers=[
        logging.FileHandler(LOG_FILE, encoding="utf-8"),
        logging.StreamHandler(sys.stdout),
    ],
)
log = logging.getLogger(__name__)

# ─── Archivos de estado / progreso ────────────────────────────────────────────
PROGRESO_FILE = Path(__file__).parent / "progreso_procesadores.json"
FUENTES_FILE  = Path(__file__).parent / "fuentes_procesadores.json"   # registro de qué fuente usó cada uno

# ─── Cliente Anthropic (lazy import) ──────────────────────────────────────────
_anthropic_client = None

def get_anthropic_client():
    global _anthropic_client
    if _anthropic_client is None:
        try:
            from anthropic import Anthropic
            api_key = os.getenv("ANTHROPIC_API_KEY", "")
            if not api_key:
                log.warning("ANTHROPIC_API_KEY no configurada — el fallback Claude no funcionará.")
            _anthropic_client = Anthropic(api_key=api_key)
        except ImportError:
            log.error("Paquete 'anthropic' no instalado. Ejecuta: pip install anthropic")
            _anthropic_client = None
    return _anthropic_client


# ─── Normalización del nombre ──────────────────────────────────────────────────

def normalizar_slug(nombre_raw: str) -> str:
    """Convierte 'INTEL CORE I7-14700' → 'intel-core-i7-14700'"""
    nombre = nombre_raw.lower().strip()
    nombre = re.sub(r"\s+", "-", nombre)
    nombre = re.sub(r"[^a-z0-9\-]", "", nombre)
    nombre = re.sub(r"-{2,}", "-", nombre)       # colapsar dobles guiones
    return nombre.strip("-")


# ─── Capa 1: Nanoreview ────────────────────────────────────────────────────────

def buscar_nanoreview(nombre_raw: str) -> str | None:
    slug = normalizar_slug(nombre_raw)
    url  = f"http://localhost:3000/scrape?slug={slug}"
    try:
        # Aumentamos el timeout porque Puppeteer puede tardar más, especialmente si hay desafío de Cloudflare
        r = requests.get(url, timeout=45)
        if r.status_code == 200:
            data = r.json()
            resultado = formatear_nanoreview(data)
            if resultado:
                return resultado
        else:
            log.debug(f"Nanoreview scraper error HTTP {r.status_code}: {r.text[:200]}")
    except Exception as e:
        log.debug(f"Nanoreview error para '{nombre_raw}': {e}")
    return None


def formatear_nanoreview(data: dict) -> str | None:
    """
    Construye el string de descripcion_2 desde la respuesta de Nanoreview.
    Formato esperado:
      '20 Núcleos, 28 Hilos, 2.1 GHz Up To 5.4 GHz, Intel UHD 770, LGA1700, L2: 28MB, L3: 33MB, 65W, 2024'
    """
    cores   = data.get("cores")   or data.get("physical_cores")
    threads = data.get("threads") or data.get("logical_processors")
    base    = data.get("base_clock")  or data.get("base_frequency")
    boost   = data.get("boost_clock") or data.get("max_turbo_frequency")
    gpu     = data.get("integrated_gpu") or data.get("gpu_name") or ""
    socket  = data.get("socket") or data.get("sockets_supported") or "?"
    l2      = data.get("l2_cache") or data.get("cache_l2") or "?"
    l3      = data.get("l3_cache") or data.get("cache_l3") or "?"
    tdp     = data.get("tdp")  or data.get("processor_base_power") or "?"
    year    = data.get("launch_year") or data.get("launch_date", "")[:4] if data.get("launch_date") else data.get("launch_year", "?")

    # Validar que al menos tengamos núcleos y frecuencia base
    if not cores or not base:
        return None

    gpu_str = f", {gpu}" if str(gpu).strip() else ""
    return (
        f"{cores} Núcleos, {threads} Hilos, "
        f"{base} GHz Up To {boost} GHz"
        f"{gpu_str}, "
        f"{socket}, "
        f"L2: {l2}, L3: {l3}, "
        f"{tdp}W, {year}"
    )


# ─── Capa 2: Intel ARK ────────────────────────────────────────────────────────

def buscar_intel_ark(nombre_raw: str) -> str | None:
    """
    Intel ARK tiene un endpoint de búsqueda interno que devuelve JSON.
    Ejemplo para 'INTEL CORE I7-14700':
    https://ark.intel.com/libs/ark/services/auth/html/products.en.json?q=i7-14700
    """
    # Extraer solo el número de modelo (i7-14700, i5-13400, etc.)
    match = re.search(r'(i\d-\d{4,5}[A-Z]*|xeon\s+\w+)', nombre_raw, re.IGNORECASE)
    if not match:
        return None
    
    modelo = match.group(1).lower()  # "i7-14700"
    url = f"https://ark.intel.com/libs/ark/services/auth/html/products.en.json?q={modelo}"
    
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept': 'application/json',
            'Referer': 'https://ark.intel.com/'
        }
        r = requests.get(url, headers=headers, timeout=15)
        if r.status_code == 200:
            data = r.json()
            if data and isinstance(data, list) and len(data) > 0:
                return formatear_desde_ark(data[0], nombre_raw)
    except Exception as e:
        log.error(f"Intel ARK error para '{nombre_raw}': {e}")
    
    return None


def formatear_desde_ark(producto, nombre_raw):
    """Extrae los campos clave del JSON de Intel ARK"""
    specs = {}
    for categoria, clave, valor in producto.get('specs', []):
        specs[clave] = valor
    
    nucleos  = specs.get('# of Cores', '?')
    hilos    = specs.get('# of Threads', '?')
    base     = specs.get('Processor Base Frequency', '?').replace(' GHz', '')
    boost    = specs.get('Max Turbo Frequency', '?').replace(' GHz', '')
    l2       = specs.get('L2 Cache', '?')
    l3       = specs.get('L3 Cache', '?')
    tdp      = specs.get('TDP', '?').replace(' W', '')
    socket   = specs.get('Sockets Supported', '?')
    gpu      = specs.get('Processor Graphics', '')
    year     = specs.get('Launch Date', '?')[:4]  # "Q4 2023" → "2023"
    
    gpu_str = f", {gpu}" if gpu and gpu != 'Not Included' else ''
    
    return (f"{nucleos} Núcleos, {hilos} Hilos, {base} GHz Up To {boost} GHz"
            f"{gpu_str}, {socket}, L2: {l2}, L3: {l3}, {tdp}W, {year}")


# ─── Capa 3: Claude (fallback) ────────────────────────────────────────────────

def buscar_con_claude(nombre_raw: str) -> str | None:
    # Claude desactivado intencionalmente.
    # Las fuentes oficiales (Nanoreview + Intel ARK) deben funcionar primero.
    log.warning(f"Todas las fuentes oficiales fallaron para: {nombre_raw}")
    return None


# ─── Orquestador ──────────────────────────────────────────────────────────────

def enriquecer_procesador(nombre_raw: str) -> tuple[str | None, str]:
    """
    Retorna (descripcion_2, fuente) donde fuente ∈ {'nanoreview', 'intel_ark', 'claude', 'fallido'}
    """
    log.info(f"Procesando: {nombre_raw}")

    # Capa 1: Nanoreview
    resultado = buscar_nanoreview(nombre_raw)
    if resultado:
        log.info(f"  ✓ Nanoreview")
        return resultado, "nanoreview"

    # Capa 2: Intel ARK (solo Intel)
    if "intel" in nombre_raw.lower():
        resultado = buscar_intel_ark(nombre_raw)
        if resultado:
            log.info(f"  ✓ Intel ARK")
            return resultado, "intel_ark"

    # Capa 3: Claude
    resultado = buscar_con_claude(nombre_raw)
    if resultado:
        log.info(f"  ✓ Claude (fallback)")
        return resultado, "claude"

    log.warning(f"  ✗ Sin resultado para: {nombre_raw}")
    return None, "fallido"


# ─── Base de datos PostgreSQL ──────────────────────────────────────────────────

def conectar_db():
    """Abre una conexión psycopg2 a PostgreSQL usando las vars de entorno."""
    try:
        import psycopg2
    except ImportError:
        log.error("psycopg2 no instalado. Ejecuta: pip install psycopg2-binary")
        sys.exit(1)

    host     = os.getenv("DB_HOST", "postgres-prod")   # nombre del contenedor en Dokploy
    port     = int(os.getenv("DB_PORT", "5432"))
    dbname   = os.getenv("DB_DATABASE", "kenya_tienda")
    user     = os.getenv("DB_USERNAME", "kenya_app")   # DB_USERNAME, no DB_USER
    password = os.getenv("DB_PASSWORD", "")

    conn = psycopg2.connect(
        host=host, port=port, dbname=dbname, user=user, password=password
    )
    conn.autocommit = False
    log.info(f"Conectado a PostgreSQL: {user}@{host}:{port}/{dbname}")
    return conn


def obtener_productos(conn, solo_vacios: bool = True, limit: int | None = None) -> list[dict]:
    """
    Devuelve lista de {'id', 'procesador'} desde la tabla productos.
    Si solo_vacios=True filtra los que ya tienen descripcion_2.
    """
    cur = conn.cursor()
    where = "WHERE (procesador IS NOT NULL AND procesador <> '')"
    if solo_vacios:
        where += " AND (descripcion_2 IS NULL OR TRIM(descripcion_2) = '')"
    lim   = f"LIMIT {limit}" if limit else ""
    cur.execute(f'SELECT id, procesador FROM productos {where} ORDER BY id {lim}')
    rows = cur.fetchall()
    cur.close()
    return [{"id": r[0], "procesador": r[1]} for r in rows]


def actualizar_descripcion_2(conn, producto_id: int, descripcion_2: str, dry_run: bool = False) -> None:
    if dry_run:
        log.info(f"  [DRY-RUN] UPDATE productos SET descripcion_2='{descripcion_2[:60]}...' WHERE id={producto_id}")
        return
    cur = conn.cursor()
    cur.execute(
        "UPDATE productos SET descripcion_2 = %s WHERE id = %s",
        (descripcion_2, producto_id),
    )
    cur.close()


# ─── Carga / guardado de progreso ─────────────────────────────────────────────

def cargar_progreso() -> dict:
    """Lee el archivo de progreso para poder reanudar."""
    if PROGRESO_FILE.exists():
        with open(PROGRESO_FILE, encoding="utf-8") as f:
            data = json.load(f)
        log.info(f"Progreso previo cargado: {len(data)} entradas")
        return data  # {nombre_raw: descripcion_2}
    return {}


def guardar_progreso(cache: dict) -> None:
    with open(PROGRESO_FILE, "w", encoding="utf-8") as f:
        json.dump(cache, f, ensure_ascii=False, indent=2)


def cargar_fuentes() -> dict:
    if FUENTES_FILE.exists():
        with open(FUENTES_FILE, encoding="utf-8") as f:
            return json.load(f)
    return {}


def guardar_fuentes(fuentes: dict) -> None:
    with open(FUENTES_FILE, "w", encoding="utf-8") as f:
        json.dump(fuentes, f, ensure_ascii=False, indent=2)


# ─── Proceso principal ─────────────────────────────────────────────────────────

PROCESADORES_TEST = [
    "INTEL CORE I7-14700",
    "AMD RYZEN 7 8700G",
    "AMD RYZEN 5 5600X",
    "INTEL CORE I5-13400",
    "AMD RYZEN 9 7950X",
]


def run(dry_run: bool = False, test: bool = False, solo_vacios: bool = True, limit: int | None = None):
    print("=" * 65)
    print("  Kenya — Enriquecedor de Procesadores (descripcion_2)")
    print(f"  {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    if dry_run:
        print("  ⚠️  MODO DRY-RUN — no se modificará la base de datos")
    print("=" * 65)

    # ── Conectar BD ──
    conn = conectar_db()

    # ── Cargar progreso / cache ──
    cache   = cargar_progreso()    # nombre_raw → descripcion_2
    fuentes = cargar_fuentes()     # nombre_raw → fuente

    try:
        # ── Obtener productos ──
        if test:
            productos = [{"id": None, "procesador": p} for p in PROCESADORES_TEST]
            log.info(f"MODO TEST: {len(productos)} procesadores de ejemplo")
        else:
            productos = obtener_productos(conn, solo_vacios=solo_vacios, limit=limit)
            log.info(f"Productos a procesar: {len(productos)}")

        if not productos:
            log.info("No hay productos que procesar. ¡Listo!")
            return

        stats = {"nanoreview": 0, "intel_ark": 0, "claude": 0, "fallido": 0, "cache": 0}
        actualizados = 0

        for i, producto in enumerate(productos, 1):
            nombre = str(producto.get("procesador", "")).strip()
            pid    = producto.get("id")

            if not nombre:
                continue

            # ── Usar cache si ya lo procesamos antes ──
            if nombre in cache:
                descripcion_2 = cache[nombre]
                fuente = fuentes.get(nombre, "cache")
                log.info(f"[{i}/{len(productos)}] Cache hit: {nombre}")
                stats["cache"] += 1
            else:
                descripcion_2, fuente = enriquecer_procesador(nombre)
                time.sleep(0.5)   # rate-limit cortés

                if descripcion_2:
                    cache[nombre]   = descripcion_2
                    fuentes[nombre] = fuente
                    stats[fuente]  += 1
                else:
                    stats["fallido"] += 1
                    log.warning(f"Sin datos para: {nombre}")
                    continue

            # ── Actualizar BD (si hay id real) ──
            if pid is not None and descripcion_2:
                actualizar_descripcion_2(conn, pid, descripcion_2, dry_run=dry_run)
                actualizados += 1

            # ── Commit y guardado de progreso cada 50 ──
            if i % 50 == 0:
                if not dry_run:
                    conn.commit()
                    log.info(f"  💾 Commit parcial — {actualizados} filas actualizadas hasta ahora")
                guardar_progreso(cache)
                guardar_fuentes(fuentes)
                log.info(f"  💾 Progreso guardado ({i}/{len(productos)})")

        # ── Commit final ──
        if not dry_run:
            conn.commit()
        guardar_progreso(cache)
        guardar_fuentes(fuentes)

    except KeyboardInterrupt:
        log.warning("⚠️  Interrumpido por el usuario — guardando progreso...")
        if not dry_run:
            conn.commit()
        guardar_progreso(cache)
        guardar_fuentes(fuentes)
    finally:
        conn.close()

    # ── Resumen final ──
    print("\n" + "=" * 65)
    print("  RESUMEN FINAL")
    print("=" * 65)
    print(f"  Productos procesados : {len(productos)}")
    print(f"  Actualizados en BD   : {actualizados}")
    print(f"  Desde cache          : {stats['cache']}")
    print(f"  Fuente Nanoreview    : {stats['nanoreview']}")
    print(f"  Fuente Intel ARK     : {stats['intel_ark']}")
    print(f"  Fuente Claude        : {stats['claude']}")
    print(f"  Fallidos             : {stats['fallido']}")
    print(f"\n  Log completo         : {LOG_FILE}")
    print(f"  Progreso/cache       : {PROGRESO_FILE}")
    print(f"  Registro de fuentes  : {FUENTES_FILE}")
    print("=" * 65)

    if test:
        print("\n  RESULTADOS DEL TEST:")
        for nombre in PROCESADORES_TEST:
            val = cache.get(nombre, "(sin resultado)")
            fuente = fuentes.get(nombre, "?")
            print(f"  [{fuente:12s}] {nombre}")
            print(f"    → {val}\n")


# ─── CLI ──────────────────────────────────────────────────────────────────────

def parse_args():
    parser = argparse.ArgumentParser(
        description="Enriquece el campo descripcion_2 con specs de procesadores",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Ejemplos:
  python enriquecer_procesadores.py --test
  python enriquecer_procesadores.py --dry-run --limit 10
  python enriquecer_procesadores.py --solo-vacios
  python enriquecer_procesadores.py
        """,
    )
    parser.add_argument(
        "--dry-run", action="store_true",
        help="Muestra qué haría sin modificar la base de datos",
    )
    parser.add_argument(
        "--test", action="store_true",
        help="Prueba solo con los 5 procesadores de ejemplo, sin tocar la BD",
    )
    parser.add_argument(
        "--solo-vacios", action="store_true", default=True,
        help="(Por defecto ON) Solo procesa productos sin descripcion_2",
    )
    parser.add_argument(
        "--todos", action="store_true",
        help="Procesa TODOS los productos con procesador, incluso si ya tienen descripcion_2",
    )
    parser.add_argument(
        "--limit", type=int, default=None, metavar="N",
        help="Limita el número de productos a procesar",
    )
    return parser.parse_args()


if __name__ == "__main__":
    args = parse_args()

    # --todos anula --solo-vacios
    solo_vacios = not args.todos

    run(
        dry_run=args.dry_run,
        test=args.test,
        solo_vacios=solo_vacios,
        limit=args.limit,
    )
