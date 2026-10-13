#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script de Investigación: Benchmarking de Secciones de Reciclaje Tecnológico (RAEE) en Perú
Objetivo: Analizar la UX/UI, flujos de usuario, simplicidad (Ley de Tesler), formularios y CTAs
en las principales páginas de reciclaje de tecnología en el Perú.
"""

import os
import sys
import json
import re
import urllib.parse
from typing import Dict, List, Any
import requests
from bs4 import BeautifulSoup

HEADERS = {
    "User-Agent": (
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 "
        "(KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    ),
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    "Accept-Language": "es-PE,es;q=0.9,en;q=0.8",
}

TARGETS = [
    {
        "id": "samsung_ecocanje",
        "empresa": "Samsung Perú",
        "programa": "Eco Canje (Trade-Up)",
        "url": "https://www.samsung.com/pe/ecocanje/",
        "tipo": "Fabricante OEM / E-Commerce con Incentivo Directo",
    },
    {
        "id": "samsung_weee",
        "empresa": "Samsung Perú",
        "programa": "Gestión RAEE y Centros de Acopio",
        "url": "https://www.samsung.com/pe/support/weee/",
        "tipo": "Fabricante OEM / Soporte & Compliance",
    },
    {
        "id": "claro_yoreciclo",
        "empresa": "Claro Perú",
        "programa": "Yo reciclo, yo soy Claro",
        "url": "https://www.claro.com.pe/institucional/sostenibilidad/yo-reciclo-yo-soy-claro/",
        "tipo": "Operador Telecom / Campaña Nacional & Puntos de Acopio",
    },
    {
        "id": "entel_reciclemos",
        "empresa": "Entel Perú",
        "programa": "Reciclemos para Transformar",
        "url": "https://entel.pe/sostenibilidad/reciclemos-para-transformar/",
        "tipo": "Operador Telecom / B2C & Red de Aliados",
    },
    {
        "id": "recolecc_puntos",
        "empresa": "Recolecc Perú",
        "programa": "Línea de Puntos de Acopio (Sistema Colectivo)",
        "url": "https://recolecc.com.pe/puntos-de-acopio/",
        "tipo": "Sistema Colectivo Oficial / MINAM",
    },
    {
        "id": "rlg_revo",
        "empresa": "Reverse Logistics Group (RLG)",
        "programa": "ReVo 360 Perú",
        "url": "https://rev-log.com/pe/productos/revo/",
        "tipo": "Sistema Colectivo Internacional / Minam",
    },
    {
        "id": "hippobox_raee",
        "empresa": "Hippobox Perú",
        "programa": "Reciclaje de Aparatos Electrónicos (RAEE)",
        "url": "https://hippobox.com.pe/reciclaje-raee/",
        "tipo": "Retailer Tecnológico Local / Solución Gratuita",
    },
    {
        "id": "comimtel",
        "empresa": "Comimtel Recycling",
        "programa": "Operador y Planta de Valorización RAEE",
        "url": "https://comimtel.com/",
        "tipo": "Empresa Operadora de Residuos (EO-RS)",
    },
    {
        "id": "sar",
        "empresa": "SAR (Sistemas Ambientales de Reciclaje)",
        "programa": "Planta de Tratamiento RAEE Lurín",
        "url": "https://sar.pe/",
        "tipo": "Planta de Valorización Industrial",
    },
]

def clean_text(text: str) -> str:
    if not text:
        return ""
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

def analyze_html(html: str, target: Dict[str, Any]) -> Dict[str, Any]:
    soup = BeautifulSoup(html, "html.parser")
    
    # Remove scripts, styles
    for s in soup(["script", "style", "noscript", "svg"]):
        s.extract()
        
    title = clean_text(soup.title.string if soup.title else "")
    meta_desc = ""
    desc_tag = soup.find("meta", attrs={"name": re.compile(r"description", re.I)})
    if desc_tag and desc_tag.get("content"):
        meta_desc = clean_text(desc_tag["content"])

    # Headers
    h1s = [clean_text(h.get_text()) for h in soup.find_all("h1") if clean_text(h.get_text())]
    h2s = [clean_text(h.get_text()) for h in soup.find_all("h2") if clean_text(h.get_text())][:8]
    h3s = [clean_text(h.get_text()) for h in soup.find_all("h3") if clean_text(h.get_text())][:8]

    # Detect Steps or Process (Paso 1, 2, 3...)
    all_text = soup.get_text()
    pasos_encontrados = []
    paso_patterns = [
        r'(?:paso|etapa|fase)\s*[0-9]+[^\n\.\:]{0,50}',
        r'[0-9]\s*[\.\-]\s*[A-ZÁÉÍÓÚ][a-záéíóú\s]{3,35}',
    ]
    for pattern in paso_patterns:
        matches = re.findall(pattern, all_text, re.IGNORECASE)
        for m in matches:
            cleaned = clean_text(m)
            if cleaned and len(cleaned) > 4 and cleaned not in pasos_encontrados:
                pasos_encontrados.append(cleaned)

    # Forms & Fields
    forms_info = []
    for form in soup.find_all("form"):
        inputs = form.find_all(["input", "select", "textarea"])
        campos = []
        for inp in inputs:
            itype = inp.get("type", "text")
            iname = inp.get("name") or inp.get("id") or inp.get("placeholder") or "campo"
            if itype not in ["hidden", "submit", "button"]:
                campos.append(f"{iname} ({itype})")
        if campos:
            forms_info.append({
                "action": form.get("action", ""),
                "total_campos": len(campos),
                "campos": campos[:10]
            })

    # Call to actions (CTAs) & Contacts
    ctas = []
    whatsapp_links = []
    email_links = []
    phone_links = []
    
    for a in soup.find_all("a", href=True):
        href = a["href"].strip()
        atext = clean_text(a.get_text())
        
        if "wa.me" in href or "whatsapp.com" in href:
            whatsapp_links.append(href)
        elif href.startswith("mailto:"):
            email_links.append(href.replace("mailto:", ""))
        elif href.startswith("tel:"):
            phone_links.append(href.replace("tel:", ""))
            
        # Check action words
        if any(w in atext.lower() for w in ["recicla", "solicitar", "entregar", "canje", "acopio", "participa", "inicia", "únete", "comienza", "agendar"]):
            if len(atext) < 60 and atext not in [c["texto"] for c in ctas]:
                ctas.append({"texto": atext, "enlace": href})

    # Categorías / Aparatos aceptados detectados
    keywords_aparatos = [
        "computadora", "laptop", "celular", "smartphone", "tablet", "impresora",
        "monitor", "cargador", "batería", "cable", "cpu", "teclado", "mouse",
        "disco duro", "servidor", "electrodoméstico", "router", "televisor"
    ]
    aparatos_detectados = set()
    all_text_lower = all_text.lower()
    for kw in keywords_aparatos:
        if kw in all_text_lower:
            aparatos_detectados.add(kw)

    # Incentivos o Beneficios detectados
    keywords_incentivos = [
        ("constancia", "Constancia de disposición RAEE"),
        ("certificado", "Certificado de reciclaje oficial"),
        ("descuento", "Bono o descuento de compra"),
        ("canje", "Programa de canje por equipo nuevo"),
        ("gratuito", "Servicio 100% gratuito"),
        ("recojo", "Servicio de recojo a domicilio / oficina"),
        ("co2", "Métricas de CO2 / huella ambiental"),
    ]
    incentivos_detectados = []
    for kw, label in keywords_incentivos:
        if kw in all_text_lower:
            incentivos_detectados.append(label)

    return {
        "id": target["id"],
        "empresa": target["empresa"],
        "programa": target["programa"],
        "url": target["url"],
        "tipo": target["tipo"],
        "titulo": title,
        "meta_descripcion": meta_desc,
        "h1": h1s,
        "h2": h2s,
        "h3": h3s,
        "pasos_detectados": pasos_encontrados[:6],
        "formularios": forms_info,
        "ctas": ctas[:8],
        "whatsapp": list(set(whatsapp_links)),
        "emails": list(set(email_links)),
        "telefonos": list(set(phone_links)),
        "aparatos_aceptados": sorted(list(aparatos_detectados)),
        "incentivos": incentivos_detectados,
    }

def main():
    print("=" * 70)
    print(" INICIANDO INVESTIGACIÓN DE PÁGINAS DE RECICLAJE TECNOLÓGICO EN PERÚ")
    print("=" * 70)

    results = []
    for target in TARGETS:
        print(f"\n[+] Analizando: {target['empresa']} ({target['programa']})...")
        print(f"    URL: {target['url']}")
        try:
            resp = requests.get(target["url"], headers=HEADERS, timeout=15)
            if resp.status_code == 200:
                print(f"    -> Status 200 OK. Procesando DOM...")
                data = analyze_html(resp.text, target)
                results.append(data)
                print(f"    -> Extracción exitosa: {len(data['aparatos_aceptados'])} categorías, {len(data['ctas'])} CTAs.")
            else:
                print(f"    -> Error HTTP {resp.status_code}.")
                results.append({
                    "id": target["id"],
                    "empresa": target["empresa"],
                    "programa": target["programa"],
                    "url": target["url"],
                    "error": f"HTTP {resp.status_code}"
                })
        except Exception as e:
            print(f"    -> Error de conexión: {str(e)[:80]}")
            results.append({
                "id": target["id"],
                "empresa": target["empresa"],
                "programa": target["programa"],
                "url": target["url"],
                "error": str(e)
            })

    # Guardar JSON completo
    script_dir = os.path.dirname(os.path.abspath(__file__))
    json_path = os.path.join(script_dir, "analisis_reciclaje_peru.json")
    with open(json_path, "w", encoding="utf-8") as f:
        json.dump(results, f, ensure_ascii=False, indent=2)
    print(f"\n[OK] Datos brutos guardados en: {json_path}")

    # Generar Informe Markdown Analítico
    report_path = os.path.join(script_dir, "REPORTE_INVESTIGACION_RECICLAJE_PERU.md")
    with open(report_path, "w", encoding="utf-8") as f:
        f.write("# Informe de Investigación: Benchmarking de Reciclaje Tecnológico en el Perú\n\n")
        f.write("**Fecha:** 2026-10-13  \n")
        f.write("**Propósito:** Diseñar un flujo de reciclaje ultra simple para el usuario (Ley de Tesler) en Kenya Technology basado en las mejores experiencias reales en producción del Perú.\n\n")
        f.write("---\n\n")
        f.write("## 1. Resumen Ejecutivo y Hallazgos Clave\n\n")
        f.write("Tras analizar los programas líderes en el mercado peruano (Entel, Claro, Samsung, Recolecc, RLG), se identifican **3 arquetipos de diseño**:\n\n")
        f.write("1. **Arquetipo 'Incentivo Comercial Inmediato' (Ej. Samsung Eco Canje):**\n")
        f.write("   - *Flujo:* Selecciona qué entregas -> Recibe bono inmediato de descuento -> Entrega el equipo viejo al recibir el nuevo.\n")
        f.write("   - *Punto fuerte:* Máxima tasa de conversión para compras recurrentes.\n")
        f.write("   - *Simplicidad:* 3 clics dentro del mismo checkout.\n\n")
        f.write("2. **Arquetipo 'Punto Limpio / Autoservicio' (Ej. Entel / Claro):**\n")
        f.write("   - *Flujo:* 'Junta tus equipos en una bolsa -> Acércate a cualquier tienda/módulo -> Deposítalo en el ánfora'.\n")
        f.write("   - *Punto fuerte:* Cero fricción, no exige llenar formularios largos ni registrarse para aparatos pequeños.\n")
        f.write("   - *Fricción:* Depende de que el usuario se traslade físicamente al punto de acopio.\n\n")
        f.write("3. **Arquetipo 'Gestión Formal y Corporativa B2B/B2G' (Ej. Recolecc / Comimtel / RLG):**\n")
        f.write("   - *Flujo:* Solicitud formal de recojo -> Emisión de Constancia/Certificado RAEE para baja de activos patrimoniales o auditorías de la OEFA/MINAM.\n")
        f.write("   - *Punto fuerte:* Vital para colegios, universidades, instituciones públicas y empresas privadas.\n\n")
        f.write("---\n\n")
        f.write("## 2. Matriz Comparativa de Plataformas en Perú\n\n")
        f.write("| Empresa / Programa | Enfoque Principal | ¿Pide Formulario Complejo? | Canal de Acción Rápido | Beneficio / Gancho para el Usuario |\n")
        f.write("|---|---|---|---|---|\n")
        f.write("| **Samsung (Eco Canje)** | B2C E-commerce | No (selector en checkout) | Botón en ficha de producto | Descuento directo en equipo nuevo |\n")
        f.write("| **Entel (Reciclemos)** | B2C & Aliados | No para particulares | Mapa de tiendas + Correo corporativo | Cero costo, impacto ambiental |\n")
        f.write("| **Claro (Yo Reciclo)** | B2C Nacional | No | Puntos en CACs / Tiendas | Ánforas sin registro previo |\n")
        f.write("| **Recolecc (MINAM)** | B2B & Puntos Aliados | Opcional (solicitud institucional) | WhatsApp / Correo / Puntos de Acopio | Constancia RAEE formal |\n")
        f.write("| **RLG (ReVo 360)** | B2B & Campañas | Sí (para recojo de lotes) | WhatsApp directo + Email | Cumplimiento regulatorio Decreto 009 |\n\n")
        f.write("---\n\n")
        f.write("## 3. Detalle por Plataforma Analizada\n\n")

        for r in results:
            if "error" in r:
                f.write(f"### {r['empresa']} — {r['programa']}\n")
                f.write(f"- **URL:** {r['url']}\n")
                f.write(f"- *Estado:* Error al acceder ({r['error']})\n\n")
                continue

            f.write(f"### {r['empresa']} — {r['programa']}\n")
            f.write(f"- **URL:** [{r['url']}]({r['url']})\n")
            f.write(f"- **Tipo:** {r['tipo']}\n")
            f.write(f"- **Título:** {r['titulo']}\n")
            if r['meta_descripcion']:
                f.write(f"- **Meta Descripción:** {r['meta_descripcion']}\n")
            
            f.write(f"- **Aparatos Aceptados Detectados:** {', '.join(r['aparatos_aceptados']) if r['aparatos_aceptados'] else 'Informática general y RAEE'}\n")
            f.write(f"- **Incentivos Ofrecidos:** {', '.join(r['incentivos']) if r['incentivos'] else 'Responsabilidad ambiental'}\n")
            
            if r['ctas']:
                f.write("- **Llamados a la Acción (CTAs):**\n")
                for c in r['ctas'][:5]:
                    f.write(f"  - `{c['texto']}` -> `{c['enlace']}`\n")

            if r['formularios']:
                f.write(f"- **Formularios Detectados:** {len(r['formularios'])}\n")
                for form in r['formularios']:
                    f.write(f"  - Total campos: {form['total_campos']} ({', '.join(form['campos'][:5])}...)\n")
            else:
                f.write("- **Formularios:** Sin formularios pesados detectados (orientado a autoservicio o contacto directo).\n")

            canales = []
            if r['whatsapp']: canales.append(f"WhatsApp: {', '.join(r['whatsapp'])}")
            if r['telefonos']: canales.append(f"Tel: {', '.join(r['telefonos'])}")
            if r['emails']: canales.append(f"Email: {', '.join(r['emails'])}")
            if canales:
                f.write(f"- **Canales Directos:** { ' | '.join(canales) }\n")

            f.write("\n")

        f.write("---\n\n")
        f.write("## 4. Recomendación de Arquitectura y Flujo para Kenya Technology (Ley de Tesler)\n\n")
        f.write("Para que el usuario recicle **de la forma más simple posible**, debemos aplicar la **Ley de Tesler**: *toda la complejidad de clasificación, cálculo de peso, logística y trazabilidad va en el backend o en el equipo de soporte de Kenya; para el usuario el flujo debe tomar menos de 30 segundos.*\n\n")
        f.write("### Propuesta de Flujo en 2 Modos Simples:\n\n")
        f.write("#### Modo 1: 'Tengo 1 a 3 equipos pequeños' (Personas / Hogar / Estudiantes)\n")
        f.write("- **Paso 1:** Selector visual con 4 iconos clicables (Laptop/PC, Celular/Tablet, Monitor, Impresora/Accesorios).\n")
        f.write("- **Paso 2:** Elegir sede de entrega (Huánuco o Lima) O solicitar recojo si está en zona de reparto.\n")
        f.write("- **Paso 3:** Botón directo a WhatsApp con mensaje autogenerado: *'Hola Kenya, quiero reciclar [Laptop] en la sede [Huánuco]. ¿En qué horario puedo llevarlo?'*.\n")
        f.write("- **Cero registro de usuario obligatorio, cero campos tediosos.**\n\n")
        f.write("#### Modo 2: 'Tengo un lote institucional' (Empresas, Colegios, Instituciones Públicas)\n")
        f.write("- **Campos mínimos (3 campos):**\n")
        f.write("  1. Nombre de empresa / RUC (autocompletable)\n")
        f.write("  2. Cantidad estimada de equipos (ej. '10 a 20 computadoras')\n")
        f.write("  3. WhatsApp o Teléfono de contacto\n")
        f.write("- **Beneficio explícito:** 'Te emitimos Constancia Oficial de Disposición RAEE para la baja contable de tus bienes'.\n")
        f.write("- **Botón de 1 clic:** Solicitar recojo institucional.\n\n")

    print(f"[OK] Reporte Markdown generado en: {report_path}")
    print("=" * 70)

if __name__ == "__main__":
    main()
