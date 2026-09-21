# 🧠 PROMPT: Implementación de Filtros Dinámicos Inteligentes (Cero Hardcoding) para CEAM AUDITOR

> **Instrucciones para el Agente / Desarrollador en `CEAM AUDITOR`**:  
> Copia y pega el siguiente prompt en el asistente de tu proyecto `CEAM_AUDITOR_2.0` (FastAPI / SQLAlchemy / React) para reemplazar los filtros manuales/estáticos por el motor dinámico de búsqueda y agregación de especificaciones.

---

```markdown
# 🎯 TAREA: Reemplazar Filtros Manuales por Motor de Filtros Dinámicos Basados en Datos Reales

## 🚨 EL PROBLEMA ACTUAL
Actualmente, los filtros de búsqueda y selección de piezas/fichas se están colocando de forma **manual / estática** (listas fijas en frontend o arrays hardcodeados en backend).
Esto causa errores críticos en producción:
1. **Opciones fantasma**: Muestra opciones que tienen 0 productos en existencia o que no pertenecen a la categoría/modelo actual, llevando a búsquedas vacías ("0 resultados").
2. **Piezas nuevas invisibles**: Cuando se importan nuevas piezas, componentes o modelos desde las fichas técnicas, **no aparecen en los filtros** a menos que un programador modifique el código a mano.
3. **Fallo por ruido de marketing en strings**: Los nombres de piezas en Perú Compras / Fichas vienen con variaciones (ej: `"NVIDIA RTX 4060 8GB OC Edition"`, `"RTX 4060 8GB Gaming X"`, `"16 GB DDR4 3200MHz"`, `"16GB DDR4  3200"`). Si el filtro manual busca `WHERE gpu = 'RTX 4060'`, la base de datos **no encuentra nada** por falta de match exacto.

---

## 🏛️ ARQUITECTURA DE LA SOLUCIÓN: Los 4 Pilares de Kenya Tienda

Debes implementar exactamente la misma arquitectura dinámica que opera en el catálogo de Kenya Tienda (`CatalogoController` + `aside-detallemod`):

```
                                  FLUJO DINÁMICO
┌─────────────────────────┐     1. Consulta BD     ┌─────────────────────────┐
│     Base de Datos       │ ─────────────────────> │  Extractor Dinámico     │
│ (Productos vigentes     │  DISTINCT(columna)     │  Acotado por modelo/cat │
│  no suspendidos)        │                        └──────────┬──────────────┘
└─────────────────────────┘                                   │
             ▲                                                │ 2. Pipeline Canónico
             │                                                ▼
┌────────────┴────────────┐                        ┌─────────────────────────┐
│   Búsqueda Inversa      │                        │  Normalizador Canónico  │
│ WHERE col IN [crudos]   │ <───────────────────── │  Limpia GHz, OC, Gaming │
│ (100% match exacto BD)  │   3. Expansión Inversa │  Consolida opciones UI  │
└─────────────────────────┘      al filtrar        └─────────────────────────┘
```

### 1. Extracción Dinámica y Contextual (Cero Hardcoding)
- Las opciones disponibles para cada filtro **se calculan en tiempo de ejecución** desde la base de datos para la categoría, marca o modelo activo.
- Regla: `WHERE activo = TRUE AND vigencia NOT IN ('SUSPENDIDA', 'ANULADA') AND TRIM(columna) NOT IN ('', 'N/A', 'NULL', 'NO APLICA')`.
- Si una pieza no existe en la base de datos, **no aparece en la lista**. Si entra una pieza nueva, **aparece inmediatamente**.

### 2. Normalización Canónica (Para visualización en UI)
Se limpia el texto crudo para que el usuario no vea 10 opciones de la misma pieza con nombres de marketing repetidos:
- **Tarjetas de Video / GPU**:
  - Elimina palabras de marketing: `OC`, `Gaming`, `Edition`, `Windforce`, `TUF`, `Eagle`, `Dedicado`, `Integrado`.
  - Normaliza espacios en VRAM: `"12GB"` -> `"12 GB"`, `"GBGDDR6"` -> `"GB GDDR6"`.
  - Resultado: `"NVIDIA GeForce RTX 4060 8GB OC Edition"` -> `"NVIDIA GeForce RTX 4060 8GB"`.
- **Procesador / CPU**:
  - Elimina frecuencias de reloj al final: `\s+\d+(\.\d+)?\s*GHZ$` (ej: `"Intel Core i7-12700 2.10 GHz"` -> `"Intel Core i7-12700"`).
- **Memoria RAM**:
  - Captura la firma estructural: `Capacidad + Tipo + Frecuencia` (ej: `"16 GB DDR4 3200 MHz"`).
- **Monitores / Suministros**:
  - Descarta puertos que digan `"No"`, `"N/A"`, `"-"`.

### 3. Búsqueda con Expansión Inversa (Reverse Lookup)
Cuando el usuario en la interfaz hace clic en `"NVIDIA GeForce RTX 4060 8GB"`:
- El backend **no** ejecuta `WHERE gpu = 'NVIDIA GeForce RTX 4060 8GB'` (porque fallaría).
- El backend toma todos los valores crudos distintos de la base de datos, los pasa por la misma función normalizadora, y obtiene la lista de todos los strings crudos reales que corresponden a esa opción canónica (ej: `["NVIDIA GeForce RTX 4060 8GB OC Edition", "NVIDIA RTX 4060 8GB Gaming X"]`).
- Luego ejecuta: `WHERE gpu IN (...)`.
- **Garantía**: El usuario encuentra el 100% de las piezas sin importar cómo vino escrita la ficha técnica.

---

## 💻 IMPLEMENTACIÓN EN PYTHON (FastAPI / SQLAlchemy)

Crea o actualiza el archivo `backend/app/services/dynamic_filters.py`:

```python
import re
from typing import Dict, List, Any, Optional
from sqlalchemy.orm import Session
from sqlalchemy import text


# ═══════════════════════════════════════════════════════════════════════════
# 1. PIPELINE DE NORMALIZACIÓN CANÓNICA
# ═══════════════════════════════════════════════════════════════════════════

def normalizar_tarjeta_video(valor: str) -> str:
    """Limpia sufijos de marketing y unifica VRAM a formato canónico."""
    if not valor:
        return ""
    v = valor.strip()
    # Quitar palabras dedicado / integrado
    v = re.sub(r'\b(dedicad[oa]s?|integrad[oa]s?)\b', '', v, flags=re.IGNORECASE)
    v = re.sub(r'\bConectividadº?\b', '', v, flags=re.IGNORECASE)
    v = re.sub(r'\s+', ' ', v).strip()
    v = re.sub(r'\s*-\s*', '-', v).strip()

    # Caso 1: Si especifica VRAM (ej. 12 GB GDDR6), conservar hasta ahí
    m_vram = re.search(r'^(.*?\d+\s*GB(?:\s*G?DDR\d[X]?)?)', v, re.IGNORECASE)
    if m_vram:
        v = m_vram.group(1).strip()
    else:
        # Caso 2: Quitar sufijos de marketing conocidos
        v = re.sub(
            r'\s+(OC|GAMING|EDITION|PLUS|SUPER|BOOST|EX|AERO|EAGLE|VISION|'
            r'WINDFORCE|PULSE|MECH|TWIN|TUF|ROG|STRIX|NITRO|PHANTOM|'
            r'REBEL|TRIPLE|DUAL|FAN|GDDR\d+|DDR\d+|V\d+|VR|READY)\b.*',
            '', v, flags=re.IGNORECASE
        ).strip()

    # Normalizar espaciado VRAM
    v = re.sub(r'(\d+)\s*GB', r'\1 GB', v, flags=re.IGNORECASE)
    v = re.sub(r'GB\s*(G?DDR\d[X]?)', r'GB \1', v, flags=re.IGNORECASE)
    v = v.lstrip('- ').strip()
    return v


def normalizar_procesador(valor: str) -> str:
    """Elimina velocidades de reloj variables al final para consolidar el modelo de CPU."""
    if not valor:
        return ""
    v = re.sub(r'\s+\d+(\.\d+)?\s*GHZ$', '', valor.strip(), flags=re.IGNORECASE)
    return re.sub(r'\s+', ' ', v).strip()


def normalizar_ram(valor: str) -> str:
    """Consolida RAM a 'X GB DDRY ZZZZ MHz'."""
    if not valor:
        return ""
    v = valor.strip()
    m = re.search(r'^(\d+\s*GB\s+DDR\d\s+\d{3,4})', v, re.IGNORECASE)
    if m:
        return f"{m.group(1).upper()} MHz"
    return re.sub(r'\s+', ' ', v.upper()).strip()


# ═══════════════════════════════════════════════════════════════════════════
# 2. EXTRACTOR DINÁMICO DE FILTROS SEGÚN CONTEXTO
# ═══════════════════════════════════════════════════════════════════════════

SPEC_COLUMNS = {
    "procesador": "Procesador",
    "ram": "Memoria RAM",
    "almacenamiento": "Almacenamiento",
    "tarjetavideo": "Tarjeta de Video",
    "sistema_operativo": "Sistema Operativo",
    "unidad_optica": "Unidad Óptica",
    "conectividad_wlan": "Conectividad WLAN",
    "video_vga": "Salida VGA",
    "video_hdmi": "Salida HDMI",
    "suite_ofimatica": "Suite Ofimática",
}

VALORES_IGNORAR = ("''", "'null'", "'NULL'", "'none'", "'NONE'", "'N/A'", "'n/a'", "'-'", "'NO APLICA'", "'N.A.'")


def obtener_filtros_disponibles(
    db: Session,
    modelo_id: Optional[int] = None,
    categoria: Optional[str] = None,
    marca: Optional[str] = None,
) -> Dict[str, Any]:
    """
    Retorna ÚNICAMENTE las opciones que realmente existen en base de datos
    para los productos vigentes, con etiquetas canónicas deduplicadas.
    """
    filtros_disponibles: Dict[str, Dict[str, Any]] = {}
    where_conditions = ["p.pagina_web = 'SI'", "COALESCE(p.vigencia, 'VIGENTE') NOT IN ('SUSPENDIDA', 'ANULADA')"]
    params: Dict[str, Any] = {}

    if modelo_id:
        where_conditions.append("p.modelo_id = :modelo_id")
        params["modelo_id"] = modelo_id
    if categoria:
        where_conditions.append("UPPER(c.nombre) LIKE :categoria")
        params["categoria"] = f"%{categoria.upper()}%"
    if marca:
        where_conditions.append("UPPER(m.nombre) LIKE :marca")
        params["marca"] = f"%{marca.upper()}%"

    where_sql = " AND ".join(where_conditions)

    for col, label in SPEC_COLUMNS.items():
        query = text(f"""
            SELECT DISTINCT TRIM(p.{col}) as valor
            FROM productos p
            LEFT JOIN categorias c ON c.id = p.categoria_id
            LEFT JOIN marcas m ON m.id = p.marca_id
            WHERE {where_sql}
              AND p.{col} IS NOT NULL
              AND TRIM(p.{col}) NOT IN ({', '.join(VALORES_IGNORAR)})
            ORDER BY valor ASC
        """)
        raw_rows = [r[0] for r in db.execute(query, params).fetchall() if r[0]]

        if not raw_rows:
            continue

        # Normalizar y consolidar opciones únicas
        canonical_map: Dict[str, bool] = {}
        for raw in raw_rows:
            if col == "tarjetavideo":
                norm = normalizar_tarjeta_video(raw)
            elif col == "procesador":
                norm = normalizar_procesador(raw)
            elif col == "ram":
                norm = normalizar_ram(raw)
            else:
                norm = raw.strip()

            if norm:
                canonical_map[norm] = True

        opciones_ordenadas = sorted(canonical_map.keys())
        if opciones_ordenadas:
            filtros_disponibles[col] = {
                "label": label,
                "options": opciones_ordenadas,
            }

    return filtros_disponibles


# ═══════════════════════════════════════════════════════════════════════════
# 3. APLICADOR DE FILTROS CON EXPANSIÓN INVERSA
# ═══════════════════════════════════════════════════════════════════════════

def aplicar_filtros_especificaciones(
    query,
    filtros_solicitados: Dict[str, List[str]],
    db: Session,
    producto_model,
):
    """
    Toma los filtros canónicos seleccionados por el usuario y los expande
    a los valores crudos reales de la BD para garantizar 100% de coincidencia.
    """
    for col, valores_seleccionados in filtros_solicitados.items():
        if not valores_seleccionados or col not in SPEC_COLUMNS:
            continue

        # Para columnas complejas con normalización, realizar reverse-lookup
        if col in ("tarjetavideo", "procesador", "ram"):
            # Obtener todos los crudos existentes en la tabla
            raw_query = text(f"""
                SELECT DISTINCT TRIM({col})
                FROM productos
                WHERE {col} IS NOT NULL AND TRIM({col}) != ''
            """)
            todos_los_crudos = [r[0] for r in db.execute(raw_query).fetchall() if r[0]]

            # Encontrar cuáles crudos normalizan a los valores elegidos
            crudos_expandidos = []
            for raw in todos_los_crudos:
                if col == "tarjetavideo":
                    norm = normalizar_tarjeta_video(raw)
                elif col == "procesador":
                    norm = normalizar_procesador(raw)
                elif col == "ram":
                    norm = normalizar_ram(raw)
                else:
                    norm = raw

                if norm in valores_seleccionados:
                    crudos_expandidos.append(raw)

            if crudos_expandidos:
                query = query.filter(getattr(producto_model, col).in_(crudos_expandidos))
            else:
                # Si no hay match posible, forzar resultado vacío
                query = query.filter(text("1 = 0"))
        else:
            # Columnas directas estándar
            query = query.filter(getattr(producto_model, col).in_(valores_seleccionados))

    return query
```

---

## 🔌 4. Endpoint FastAPI en `backend/app/api/endpoints/fichas.py`

Agrega el endpoint que consume el frontend:

```python
from fastapi import APIRouter, Depends, Query
from sqlalchemy.orm import Session
from app.db.database import get_db
from app.services.dynamic_filters import obtener_filtros_disponibles, aplicar_filtros_especificaciones

router = APIRouter()

@router.get("/filtros-dinamicos")
def get_filtros_dinamicos(
    modelo_id: Optional[int] = Query(None),
    categoria: Optional[str] = Query(None),
    marca: Optional[str] = Query(None),
    db: Session = Depends(get_db)
):
    """
    Retorna el esquema exacto de filtros aplicables en tiempo real
    sin ninguna opción manual o hardcodeada.
    """
    filtros = obtener_filtros_disponibles(
        db=db,
        modelo_id=modelo_id,
        categoria=categoria,
        marca=marca,
    )
    return {"ok": True, "filtros": filtros}
```

---

## 🎨 5. Consumo en Frontend (React / Vue)

El frontend **NUNCA** debe tener una lista de procesadores o tarjetas de video escritas a mano.  
En su lugar, renderiza dinámicamente las claves devueltas por `/filtros-dinamicos`:

```jsx
// Al cambiar de modelo o categoría, se refrescan los filtros reales disponibles
useEffect(() => {
  api.get(`/api/v1/fichas/filtros-dinamicos?modelo_id=${modeloSeleccionado}`)
     .then(res => setFiltrosDisponibles(res.data.filtros));
}, [modeloSeleccionado]);

// Renderizado 100% dinámico:
return (
  <aside className="filters-sidebar">
    {Object.entries(filtrosDisponibles).map(([colKey, { label, options }]) => (
      <div key={colKey} className="filter-group">
        <h4>{label}</h4>
        {options.map(option => (
          <label key={option} className="filter-checkbox">
            <input
              type="checkbox"
              checked={selectedFilters[colKey]?.includes(option) || false}
              onChange={() => handleToggleFilter(colKey, option)}
            />
            <span>{option}</span>
          </label>
        ))}
      </div>
    ))}
  </aside>
);
```

---

## ✅ CHECKLIST DE VERIFICACIÓN
1. [ ] **¿Se eliminaron todos los arrays estáticos de componentes/specs en el código?** (Sí, todo proviene de `obtener_filtros_disponibles`).
2. [ ] **¿Al seleccionar un filtro, la búsqueda siempre devuelve resultados reales?** (Sí, gracias a la expansión inversa de crudos).
3. [ ] **¿Al importar una nueva ficha técnica con una pieza nueva, aparece inmediatamente en el filtro sin tocar código?** (Sí, mediante `SELECT DISTINCT`).
4. [ ] **¿Los nombres en la interfaz están limpios de ruido como "OC", "Gaming Edition", "2.10 GHz"?** (Sí, gracias al pipeline canónico).
```
