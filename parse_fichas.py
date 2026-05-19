#!/usr/bin/env python3
"""
parse_fichas.py
===============
Extrae fichas de Kenya Technology desde la API REST del auditor
(https://api-auditor.sekaitech.com.pe/api/v1/fichas/).
Parsea las características del texto de descripción y genera
fichas_kenya.json listo para importar en Kenya Tienda.

Uso:
    python parse_fichas.py                        # todas las fichas Kenya
    python parse_fichas.py PU9K21WD13             # prueba con código específico
    python parse_fichas.py --estado SUSPENDIDA    # solo suspendidas

Requiere:
    pip install requests
"""

import requests
import json
import sys
import re
import time

# ─── Configuración ────────────────────────────────────────────────────────────
API_BASE     = "https://api-auditor.sekaitech.com.pe/api/v1"
MARCA_FILTER = "Kenya"
PAGE_SIZE    = 50    # la API retorna máximo 50 por página
OUTPUT_FILE  = "fichas_kenya.json"
HEADERS = {
    "User-Agent": "KenyaSync/1.0",
    "Accept": "application/json",
}

# ─── Parser de descripción ─────────────────────────────────────────────────────
# El texto de la tabla tiene el formato:
#   TIPO : PROCESADOR: X RAM: Y ALMACENAMIENTO: Z LAN: SI WLAN: NO USB: SI
#          VGA: SI HDMI: SI SIST. OPER: X UNIDAD OPTICA: NO TECLADO: SI
#          MOUSE: SI SUITE OFIMATICA: X G. F: X UNIDAD KENYA TECHNOLOGY MODELO NRO_PARTE
#          SIST. MANEJO RAEE: COLECTIVO

# Orden de tokens conocidos (para extraer hasta el siguiente token)
SPEC_TOKENS = [
    ("PROCESADOR:",           "procesador"),
    ("RAM:",                  "ram"),
    ("ALMACENAMIENTO:",       "almacenamiento"),
    ("LAN:",                  "conectividad"),
    ("WLAN:",                 "conectividad_wlan"),
    ("USB:",                  "conectividad_usb"),
    ("VGA:",                  "video_vga"),
    ("HDMI:",                 "video_hdmi"),
    ("SIST. OPER:",           "sistema_operativo"),
    ("SISTEMA OPERATIVO:",    "sistema_operativo"),
    ("UNIDAD OPTICA:",        "unidad_optica"),
    ("TECLADO:",              "teclado"),
    ("MOUSE:",                "mouse"),
    ("SUITE OFIMATICA PRE-INSTALADA:", "suite_ofimatica"),
    ("SUITE OFIMATICA:",      "suite_ofimatica"),
    ("G. F:",                 "garantia_de_fabrica"),
    ("PANTALLA:",             "pantalla"),
]

# Build regex: buscar cada token hasta el siguiente token conocido o fin de texto
_all_token_pattern = "|".join(
    re.escape(tok) for tok, _ in SPEC_TOKENS
) + r"|UNIDAD KENYA TECHNOLOGY|SIST\. MANEJO RAEE"


def parse_description(text):
    """
    Parsea el texto de descripción compacto de la ficha del auditor.
    Retorna dict con columnas de productos.
    Ej. input: "COMPUTADORA DE ESCRITORIO : PROCESADOR: INTEL CORE I7-12700 RAM: 16 GB ..."
    """
    specs = {}

    # Tipo de producto (antes del primer ":")
    tipo_m = re.match(r"^([^:]+):", text.strip())
    if tipo_m:
        specs["tipo_producto"] = tipo_m.group(1).strip()

    for (token, col) in SPEC_TOKENS:
        # Buscar token y capturar hasta el siguiente token conocido
        pattern = re.escape(token) + r"\s*(.*?)(?=" + _all_token_pattern + r"|$)"
        m = re.search(pattern, text, re.IGNORECASE | re.DOTALL)
        if m:
            val = m.group(1).strip()
            val = re.sub(r"\s+", " ", val)  # normalizar espacios
            if val:
                # Si ya existe (ej. SUITE OFIMATICA vs SUITE OFIMATICA PRE-INSTALADA), no sobreescribir
                if col not in specs:
                    specs[col] = val

    # Extraer modelo y nro_parte del bloque "UNIDAD KENYA TECHNOLOGY MODELO NRO_PARTE"
    unidad_m = re.search(
        r"UNIDAD KENYA TECHNOLOGY\s+(.+?)\s+(\S+)\s+SIST\.?\s*MANEJO",
        text, re.IGNORECASE
    )
    if unidad_m:
        modelo_raw = unidad_m.group(1).strip()
        nro_parte  = unidad_m.group(2).strip().upper()

        # El último token de modelo_raw puede ser el nro_parte repetido → quedarnos con el modelo
        specs["modelo_ficha"] = modelo_raw
        specs["nro_parte_ficha"] = nro_parte

    return specs


# ─── API del auditor ──────────────────────────────────────────────────────────

def get_all_kenya_fichas(estado=None, codigos=None):
    """
    Llama a la API REST del auditor y trae todas las fichas Kenya paginadas.
    Retorna lista de dicts con los campos raw de la API.
    """
    all_items = []
    page = 1

    while True:
        params = {"marca": MARCA_FILTER, "page": page, "page_size": PAGE_SIZE}
        if estado:
            params["estado"] = estado

        print(f"  API página {page}...", end=" ", flush=True)
        resp = requests.get(f"{API_BASE}/fichas/", params=params, headers=HEADERS, timeout=20)
        resp.raise_for_status()
        data = resp.json()

        items = data.get("items", [])
        total = data.get("total", 0)
        print(f"{len(items)} fichas (total={total})")

        if not items:
            break

        all_items.extend(items)

        # Si ya trajimos todo
        if len(all_items) >= total:
            break

        page += 1
        time.sleep(0.3)

    # Filtrar por códigos específicos si se pidieron
    if codigos:
        all_items = [i for i in all_items
                     if i.get("nro_parte_o_cdigo_nico_de_identificacin", "").upper() in codigos]

    return all_items


# ─── Procesamiento principal ───────────────────────────────────────────────────

def process_fichas(api_items):
    """
    Convierte los items raw de la API en registros listos para importar.
    """
    results = []
    for item in api_items:
        # Campos con nombres "degenerados" por pérdida de tildes en la API
        codigo    = item.get("nro_parte_o_cdigo_nico_de_identificacin", "").upper()
        descripcion = item.get("descripcin_fichaproducto", "")
        estado    = item.get("estado_ficha_producto", "OFERTADA").upper()
        categoria = item.get("categora", "") or item.get("categoria", "")
        pdf_url   = item.get("ficha_tcnica", "")
        img_url   = item.get("imagen", "")

        specs = parse_description(descripcion)

        results.append({
            "codigo_ficha":      codigo,         # = nro_parte en productos
            "estado":            estado,          # OFERTADA | SUSPENDIDA
            "categoria":         categoria,
            "descripcion_breve": descripcion,
            "pdf_url":           pdf_url,         # URL directa al PDF en Azure CDN
            "imagen_url":        img_url,
            "specs":             specs,
        })

    return results


def run(codigos_especificos=None, estado_filter=None):
    print("=" * 60)
    print("  Kenya Fichas Parser — API Auditor")
    print("=" * 60)

    codigos_set = set(codigos_especificos) if codigos_especificos else None

    print(f"\nObteniendo fichas Kenya desde API...")
    api_items = get_all_kenya_fichas(estado=estado_filter, codigos=codigos_set)
    print(f"Total fichas obtenidas: {len(api_items)}")

    results = process_fichas(api_items)

    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        json.dump(results, f, ensure_ascii=False, indent=2)

    print(f"\n{len(results)} fichas procesadas -> {OUTPUT_FILE}")

    # Preview de la primera ficha
    if results:
        sample = results[0]
        print(f"\n--- Preview: {sample['codigo_ficha']} [{sample['estado']}] ---")
        for k, v in sample["specs"].items():
            print(f"  {k}: {v}")
        if sample["pdf_url"]:
            print(f"  PDF: {sample['pdf_url']}")

    return results


if __name__ == "__main__":
    args = sys.argv[1:]

    estado_filter = None
    if "--estado" in args:
        idx = args.index("--estado")
        estado_filter = args[idx + 1].upper()
        args = [a for i, a in enumerate(args)
                if a != "--estado" and (i == 0 or args[i-1] != "--estado")]

    codigos = [a.upper() for a in args if not a.startswith("--")] or None

    run(codigos_especificos=codigos, estado_filter=estado_filter)
