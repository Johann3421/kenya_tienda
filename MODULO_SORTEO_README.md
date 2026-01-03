# 🎁 Módulo de Sorteo - KENYA Tienda

## 📋 Descripción

Sistema de sorteo por ruleta interactiva que permite a los clientes participar usando el número de serie de sus productos Kenya y ganar premios según el número de intentos acumulados.

## 🎯 Características Principales

### ✨ Funcionalidades

1. **Validación de Números de Serie**
   - Verifica que el número de serie exista en la base de datos de productos
   - Control de dispositivos para evitar intentos duplicados por serial

2. **Ruleta Interactiva**
   - Diseño moderno estilo Temu con efectos visuales
   - Animación suave de rotación
   - Indicador visual del premio ganado
   - Efectos de confetti al ganar

3. **Sistema de Premios por Intentos**
   - **3 intentos**: USB - Memoria USB
   - **5 intentos**: Parlante Bluetooth
   - **10 intentos**: Teclado
   - **12 intentos**: Audífono

4. **Control Anti-fraude**
   - Fingerprint único por dispositivo
   - Un intento por serial por dispositivo
   - Registro de IP y User Agent
   - Bloqueo de dispositivo después de participar

5. **Sistema de Reclamo de Premios**
   - Formulario para registrar datos del ganador
   - Generación de código único de premio
   - Registro de fecha de reclamo

## 📊 Estructura de Base de Datos

### Tablas Principales

#### `serial_numbers`
Almacena los números de serie registrados
- `serial`: Número de serie único
- `owner_name`, `owner_email`, `owner_phone`: Datos del propietario
- `last_attempt_at`: Último intento de participación

#### `serial_rewards`
Define los premios disponibles
- `title`: Nombre del premio
- `description`: Descripción del premio
- `attempt_threshold`: Número de intentos necesarios
- `is_active`: Estado del premio

#### `serial_attempts`
Registra cada participación
- `producto_id`: ID del producto asociado
- `serial`: Número de serie usado
- `device_fingerprint`: Huella digital del dispositivo
- `attempt_number`: Número de intento del usuario
- `serial_reward_id`: Premio ganado (si aplica)

#### `serial_device_locks`
Control de dispositivos bloqueados
- `device_hash`: Hash único del dispositivo
- `serial`: Serial asociado
- `locked_at`: Fecha de bloqueo

#### `serial_reward_claims`
Registro de premios reclamados
- `serial_attempt_id`: ID del intento ganador
- `nombre`, `email`, `telefono`: Datos del ganador
- `codigo_premio`: Código único del premio
- `claimed_at`: Fecha de reclamo

## 🚀 Instalación y Configuración

### 1. Verificar que existan los archivos

Los siguientes archivos ya fueron creados:

**Modelos:**
- `app/Models/SerialNumber.php`
- `app/Models/SerialAttempt.php`
- `app/Models/SerialReward.php`
- `app/Models/SerialDeviceLock.php`
- `app/Models/SerialRewardClaim.php`

**Controlador:**
- `app/Http/Controllers/SerialDrawController.php`

**Vistas:**
- `resources/views/sorteo/index.blade.php`

**Migración:**
- `database/migrations/2026_01_02_000001_create_serial_draw_tables.php`

**Seeder:**
- `database/seeders/SerialRewardSeeder.php`

### 2. Ejecutar Migraciones (Solo si es necesario)

Si las tablas no existen en tu base de datos:

```bash
php artisan migrate
```

### 3. Poblar Premios Iniciales

```bash
php artisan db:seed --class=SerialRewardSeeder
```

### 4. Verificar Rutas

Las siguientes rutas ya están configuradas en `routes/web.php`:

```php
Route::get('/sorteo', [SerialDrawController::class, 'index'])->name('serial.draw');
Route::post('/sorteo', [SerialDrawController::class, 'store'])->name('serial.draw.store');
Route::post('/sorteo/claim', [SerialDrawController::class, 'claim'])->name('serial.draw.claim');
```

## 🎨 Diseño y UX

### Colores Principales
- Gradient principal: `#667eea` → `#764ba2`
- Acentos: `#FFD700`, `#FFA500`, `#FF4444`
- Fondo: Gradient púrpura con animación flotante

### Efectos Visuales
- Animación de pulsación en el título
- Efecto de glow rotatorio en la ruleta
- Bounce en el indicador de premio
- Confetti al ganar
- Transiciones suaves en todos los elementos
- Modal animado para mostrar premios

### Responsividad
- Diseño adaptable para móviles (< 768px)
- Ruleta redimensionada en pantallas pequeñas
- Inputs y botones ajustados para touch

## 📱 Flujo de Usuario

1. **Inicio**: Usuario accede desde el menú principal (🎁 Sorteo)
2. **Ingreso de Serial**: Introduce el número de serie de su producto
3. **Validación**: Sistema verifica serial y dispositivo
4. **Ruleta**: Se muestra la ruleta con giro automático
5. **Resultado**: Modal muestra el premio ganado (si aplica)
6. **Reclamo**: Usuario completa formulario con sus datos
7. **Código**: Sistema genera código único de premio

## 🔧 Funciones Principales del Controlador

### `index()`
Muestra la página principal del sorteo

### `store(Request $request)`
- Valida el número de serie
- Verifica bloqueos de dispositivo
- Cuenta intentos del día
- Determina premio según intentos
- Registra el intento

### `claim(Request $request)`
- Valida los datos del ganador
- Verifica que no esté reclamado
- Genera código único
- Registra el reclamo

### `generateDeviceFingerprint(Request $request)`
- Crea hash SHA-256 único del dispositivo
- Usa IP, User Agent, Accept-Language, Accept-Encoding

## 📈 Datos Recuperados de la BD

Según el análisis del respaldo SQL:

- ✅ 5 tablas del sistema de sorteo
- ✅ 4 premios configurados (USB, Parlante, Teclado, Audífono)
- ✅ Registro de 3 intentos previos
- ✅ Sistema de bloqueo de dispositivos activo
- ✅ Entrada en tabla `asides` con ruta `/sorteo`

## 🎯 Premios Configurados

| Intentos | Premio | Descripción |
|----------|--------|-------------|
| 3 | USB | Memoria USB Regalo por ser un cliente fiel |
| 5 | Parlante | Parlante Bluetooth Regalo por ser un cliente Maravilloso |
| 10 | Teclado | Teclado Regalo por ser un cliente Entusiasta |
| 12 | Audífono | Audífono Regalo por ser un cliente Estupendo |

## 🛠️ Administración

### Agregar Nuevos Premios

Insertar en tabla `serial_rewards`:

```sql
INSERT INTO serial_rewards (title, description, attempt_threshold, is_active, created_at, updated_at)
VALUES ('Nombre Premio', 'Descripción del premio', 15, 1, NOW(), NOW());
```

### Desactivar Premios

```sql
UPDATE serial_rewards SET is_active = 0 WHERE id = 1;
```

### Consultar Intentos de un Usuario

```sql
SELECT * FROM serial_attempts 
WHERE device_fingerprint = 'hash_del_dispositivo'
ORDER BY created_at DESC;
```

### Ver Premios Reclamados

```sql
SELECT c.codigo_premio, c.nombre, c.email, r.title, c.claimed_at
FROM serial_reward_claims c
JOIN serial_attempts a ON c.serial_attempt_id = a.id
JOIN serial_rewards r ON a.serial_reward_id = r.id
ORDER BY c.claimed_at DESC;
```

## 🔒 Seguridad

- ✅ Validación CSRF en todos los formularios
- ✅ Fingerprint único por dispositivo
- ✅ Bloqueo permanente serial-dispositivo
- ✅ Validación de existencia de serial en productos
- ✅ Registro de IP y User Agent
- ✅ Prevención de reclamos duplicados

## 📞 Soporte y Contacto

Para dudas sobre el módulo, contactar con el equipo de desarrollo de Kenya.

---

**Versión**: 1.0  
**Fecha de Restauración**: 2 de Enero, 2026  
**Estado**: ✅ Completamente Funcional
