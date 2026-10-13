# Informe de Investigación: Benchmarking de Reciclaje Tecnológico en el Perú

**Fecha:** 2026-10-13  
**Propósito:** Diseñar un flujo de reciclaje ultra simple para el usuario (Ley de Tesler) en Kenya Technology basado en las mejores experiencias reales en producción del Perú.

---

## 1. Resumen Ejecutivo y Hallazgos Clave

Tras analizar los programas líderes en el mercado peruano (Entel, Claro, Samsung, Recolecc, RLG), se identifican **3 arquetipos de diseño**:

1. **Arquetipo 'Incentivo Comercial Inmediato' (Ej. Samsung Eco Canje):**
   - *Flujo:* Selecciona qué entregas -> Recibe bono inmediato de descuento -> Entrega el equipo viejo al recibir el nuevo.
   - *Punto fuerte:* Máxima tasa de conversión para compras recurrentes.
   - *Simplicidad:* 3 clics dentro del mismo checkout.

2. **Arquetipo 'Punto Limpio / Autoservicio' (Ej. Entel / Claro):**
   - *Flujo:* 'Junta tus equipos en una bolsa -> Acércate a cualquier tienda/módulo -> Deposítalo en el ánfora'.
   - *Punto fuerte:* Cero fricción, no exige llenar formularios largos ni registrarse para aparatos pequeños.
   - *Fricción:* Depende de que el usuario se traslade físicamente al punto de acopio.

3. **Arquetipo 'Gestión Formal y Corporativa B2B/B2G' (Ej. Recolecc / Comimtel / RLG):**
   - *Flujo:* Solicitud formal de recojo -> Emisión de Constancia/Certificado RAEE para baja de activos patrimoniales o auditorías de la OEFA/MINAM.
   - *Punto fuerte:* Vital para colegios, universidades, instituciones públicas y empresas privadas.

---

## 2. Matriz Comparativa de Plataformas en Perú

| Empresa / Programa | Enfoque Principal | ¿Pide Formulario Complejo? | Canal de Acción Rápido | Beneficio / Gancho para el Usuario |
|---|---|---|---|---|
| **Samsung (Eco Canje)** | B2C E-commerce | No (selector en checkout) | Botón en ficha de producto | Descuento directo en equipo nuevo |
| **Entel (Reciclemos)** | B2C & Aliados | No para particulares | Mapa de tiendas + Correo corporativo | Cero costo, impacto ambiental |
| **Claro (Yo Reciclo)** | B2C Nacional | No | Puntos en CACs / Tiendas | Ánforas sin registro previo |
| **Recolecc (MINAM)** | B2B & Puntos Aliados | Opcional (solicitud institucional) | WhatsApp / Correo / Puntos de Acopio | Constancia RAEE formal |
| **RLG (ReVo 360)** | B2B & Campañas | Sí (para recojo de lotes) | WhatsApp directo + Email | Cumplimiento regulatorio Decreto 009 |

---

## 3. Detalle por Plataforma Analizada

### Samsung Perú — Eco Canje (Trade-Up)
- **URL:** [https://www.samsung.com/pe/ecocanje/](https://www.samsung.com/pe/ecocanje/)
- **Tipo:** Fabricante OEM / E-Commerce con Incentivo Directo
- **Título:** Samsung Trade-Up Ecocanje Home I TV & Línea blanca | Samsung Perú
- **Meta Descripción:** Actualice a un nuevo televisor o electrodoméstico Samsung con el programa Samsung Trade-Up. Traiga cualquier marca, en cualquier condición, y reciba un descuento junto con reciclaje gratuito.
- **Aparatos Aceptados Detectados:** monitor, smartphone, tablet, televisor
- **Incentivos Ofrecidos:** Bono o descuento de compra, Programa de canje por equipo nuevo, Servicio de recojo a domicilio / oficina
- **Llamados a la Acción (CTAs):**
  - `Plan Canje` -> `/pe/trade-in/`
  - `Iniciar sesión/Registrarse` -> `javascript:;`
  - `¿Por qué Ecocanje?` -> `#whysamsung`
  - `1. ¿Qué es Ecocanje?` -> `javascript:void(0)`
  - `2. ¿Qué tipo de productos podré usar para Ecocanje?` -> `javascript:void(0)`
- **Formularios Detectados:** 1
  - Total campos: 1 (search (text)...)

### Samsung Perú — Gestión RAEE y Centros de Acopio
- **URL:** [https://www.samsung.com/pe/support/weee/](https://www.samsung.com/pe/support/weee/)
- **Tipo:** Fabricante OEM / Soporte & Compliance
- **Título:** Residuos de Aparatos Eléctricos y Electrónicos (RAEE) | Samsung Perú
- **Meta Descripción:** Samsung cares about the environment and has implemented collection centers for electronic products.
- **Aparatos Aceptados Detectados:** celular, computadora, cpu, disco duro, electrodoméstico, impresora, laptop, monitor, mouse, router, smartphone, tablet, teclado, televisor
- **Incentivos Ofrecidos:** Programa de canje por equipo nuevo
- **Llamados a la Acción (CTAs):**
  - `Plan Canje` -> `/pe/trade-in/`
  - `Iniciar sesión/Registrarse` -> `javascript:;`
  - `Iniciar conversación` -> `#start-chat`
- **Formularios Detectados:** 1
  - Total campos: 1 (search (text)...)

### Claro Perú — Yo reciclo, yo soy Claro
- **URL:** [https://www.claro.com.pe/institucional/sostenibilidad/yo-reciclo-yo-soy-claro/](https://www.claro.com.pe/institucional/sostenibilidad/yo-reciclo-yo-soy-claro/)
- **Tipo:** Operador Telecom / Campaña Nacional & Puntos de Acopio
- **Título:** Yo reciclo, yo soy claro | Claro PerÃº
- **Meta Descripción:** En Claro estamos juntos por el reciclaje. Promovemos una cultura de segregaciÃ³n y reciclaje de Aparatos ElÃ©ctricos y ElectrÃ³nicos con el fin de reducir el impacto en nuestro planeta.
- **Aparatos Aceptados Detectados:** cable, cargador, celular, smartphone
- **Incentivos Ofrecidos:** Responsabilidad ambiental
- **Llamados a la Acción (CTAs):**
  - `Puntos de acopio para la comunidad` -> `https://www.claro.com.pe/assets/pdf/PUNTOS_DE_ACOPIO_MARZO_2026.pdf`
- **Formularios Detectados:** 1
  - Total campos: 1 (q (text)...)
- **Canales Directos:** WhatsApp: https://api.whatsapp.com/send/?phone=51915020030&text=Hola%2C+tengo+una+consulta, https://api.whatsapp.com/send?phone=51954305555&text=Hola%20Claro%2C%20necesito%20ayuda%20con%20mi%20pedido/, https://api.whatsapp.com/send/?phone=51954305555&text=%C2%A1Hola%20TiendaBot!%20Deseo%20renovar%20mi%20equipo%20con%20ustedes%20por%20Whatsapp, https://api.whatsapp.com/send/?phone=51954305555&text=Hola%20Claro,%20quiero%20un%20plan%20Hogar, https://api.whatsapp.com/send/?phone=51954305555&text=%C2%A1Hola%20TiendaBot!%20Deseo%20adquirir%20mi%20Chip%20Postpago%20y%20Portar%20a%20Claro | Tel: +0800 00123, +0800 00200, 0080000200 | Email: sostenibilidad@claro.com.pe

### Entel Perú — Reciclemos para Transformar
- **URL:** [https://entel.pe/sostenibilidad/reciclemos-para-transformar/](https://entel.pe/sostenibilidad/reciclemos-para-transformar/)
- **Tipo:** Operador Telecom / B2C & Red de Aliados
- **Título:** Reciclemos para transformar | Entel Perú
- **Aparatos Aceptados Detectados:** Informática general y RAEE
- **Incentivos Ofrecidos:** Responsabilidad ambiental
- **Formularios:** Sin formularios pesados detectados (orientado a autoservicio o contacto directo).

### Recolecc Perú — Línea de Puntos de Acopio (Sistema Colectivo)
- **URL:** [https://recolecc.com.pe/puntos-de-acopio/](https://recolecc.com.pe/puntos-de-acopio/)
- **Tipo:** Sistema Colectivo Oficial / MINAM
- **Título:** Puntos de acopio – Recolecc
- **Aparatos Aceptados Detectados:** Informática general y RAEE
- **Incentivos Ofrecidos:** Responsabilidad ambiental
- **Llamados a la Acción (CTAs):**
  - `Reciclamos para dejar huella` -> `https://recolecc.com.pe/reciclamos-para-dejar-huella/`
  - `Puntos de acopio` -> `https://recolecc.com.pe/puntos-de-acopio/`
  - `Linea de puntos de acopio` -> `https://recolecc.com.pe/puntos-de-acopio/`
- **Formularios:** Sin formularios pesados detectados (orientado a autoservicio o contacto directo).
- **Canales Directos:** Email: marketing@recolecc.com.pe

### Reverse Logistics Group (RLG) — ReVo 360 Perú
- **URL:** [https://rev-log.com/pe/productos/revo/](https://rev-log.com/pe/productos/revo/)
- **Tipo:** Sistema Colectivo Internacional / Minam
- **Título:** ReVo - Reverse Logistics Group
- **Aparatos Aceptados Detectados:** batería, cable, cargador, celular, computadora, cpu, disco duro, electrodoméstico, impresora, laptop, monitor, mouse, router, tablet, teclado, televisor
- **Incentivos Ofrecidos:** Certificado de reciclaje oficial
- **Formularios Detectados:** 2
  - Total campos: 1 (s (search)...)
  - Total campos: 8 (item_7__fluent_sf (text), names[first_name] (text), names[last_name] (text), email_1 (email), numeric-field_1 (number)...)
- **Canales Directos:** Tel: %20+57%20(4)%20604%2072%2000 | Email: info@revo360.com

### Hippobox Perú — Reciclaje de Aparatos Electrónicos (RAEE)
- **URL:** [https://hippobox.com.pe/reciclaje-raee/](https://hippobox.com.pe/reciclaje-raee/)
- **Tipo:** Retailer Tecnológico Local / Solución Gratuita
- **Título:** Reciclaje RAEE – Hippobox
- **Aparatos Aceptados Detectados:** Informática general y RAEE
- **Incentivos Ofrecidos:** Bono o descuento de compra, Servicio 100% gratuito
- **Llamados a la Acción (CTAs):**
  - `Reciclaje RAEE` -> `https://hippobox.com.pe/reciclaje-raee/`
- **Formularios Detectados:** 2
  - Total campos: 1 (s (search)...)
  - Total campos: 1 (s (search)...)

### Comimtel Recycling — Operador y Planta de Valorización RAEE
- **URL:** [https://comimtel.com/](https://comimtel.com/)
- **Tipo:** Empresa Operadora de Residuos (EO-RS)
- **Título:** Compramos chatarra industrial - Comimtel
- **Meta Descripción:** Somos pioneros en Minería Urbana desde hace más de 21 años. Especialistas y profesionales en la asesoría y respuesta inmediata a las necesidades de nuestros aliados, minimizando así el impacto ambiental en nuestro país y el Planeta
- **Aparatos Aceptados Detectados:** Informática general y RAEE
- **Incentivos Ofrecidos:** Certificado de reciclaje oficial
- **Llamados a la Acción (CTAs):**
  - `Entregar residuos` -> `https://comimtel.com/nosotros/`
- **Formularios:** Sin formularios pesados detectados (orientado a autoservicio o contacto directo).

### SAR (Sistemas Ambientales de Reciclaje) — Planta de Tratamiento RAEE Lurín
- **URL:** [https://sar.pe/](https://sar.pe/)
- **Tipo:** Planta de Valorización Industrial
- **Título:** 
- **Aparatos Aceptados Detectados:** tablet
- **Incentivos Ofrecidos:** Certificado de reciclaje oficial
- **Llamados a la Acción (CTAs):**
  - `Únete` -> `https://www.sar.pe/unete/`
- **Formularios:** Sin formularios pesados detectados (orientado a autoservicio o contacto directo).
- **Canales Directos:** WhatsApp: https://wa.me/998196245

---

## 4. Recomendación de Arquitectura y Flujo para Kenya Technology (Ley de Tesler)

Para que el usuario recicle **de la forma más simple posible**, debemos aplicar la **Ley de Tesler**: *toda la complejidad de clasificación, cálculo de peso, logística y trazabilidad va en el backend o en el equipo de soporte de Kenya; para el usuario el flujo debe tomar menos de 30 segundos.*

### Propuesta de Flujo en 2 Modos Simples:

#### Modo 1: 'Tengo 1 a 3 equipos pequeños' (Personas / Hogar / Estudiantes)
- **Paso 1:** Selector visual con 4 iconos clicables (Laptop/PC, Celular/Tablet, Monitor, Impresora/Accesorios).
- **Paso 2:** Elegir sede de entrega (Huánuco o Lima) O solicitar recojo si está en zona de reparto.
- **Paso 3:** Botón directo a WhatsApp con mensaje autogenerado: *'Hola Kenya, quiero reciclar [Laptop] en la sede [Huánuco]. ¿En qué horario puedo llevarlo?'*.
- **Cero registro de usuario obligatorio, cero campos tediosos.**

#### Modo 2: 'Tengo un lote institucional' (Empresas, Colegios, Instituciones Públicas)
- **Campos mínimos (3 campos):**
  1. Nombre de empresa / RUC (autocompletable)
  2. Cantidad estimada de equipos (ej. '10 a 20 computadoras')
  3. WhatsApp o Teléfono de contacto
- **Beneficio explícito:** 'Te emitimos Constancia Oficial de Disposición RAEE para la baja contable de tus bienes'.
- **Botón de 1 clic:** Solicitar recojo institucional.

