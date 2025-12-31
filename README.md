# IslaTransfers – Producto 3 (Laravel)

Aplicación web desarrollada en **Laravel** para la gestión de **reservas de transfers** en la isla entre **hoteles**, **viajeros** y la central de **IslaTransfers** (administración).

Forma parte del caso práctico de la asignatura, y se centra en:

- Autenticación y autorización por **roles** (admin, hotel, viajero).
- Gestión completa de **reservas de transfer** (ida, vuelta, ida y vuelta).
- Gestión de **tarifas** y **comisiones de hoteles**.
- Paneles diferenciados para cada rol.
- Exposición de un **WebService REST** con información agregada por zonas.
- Despliegue en entorno local y en **AWS**.

---

## 1. Tecnologías utilizadas

- **PHP** 8.x
- **Laravel** 11.x
- **MySQL** 8.x
- **Composer**
- **Bootstrap 5** para la maquetación de las vistas
- **Blade Components** para layouts y componentes comunes
- **Carbon** para gestión de fechas
- **Eloquent ORM** (relaciones, scopes, mutators/casts)
- **Seeders** para generación de datos de prueba

---

## 2. Roles y funcionalidades

### 2.1. Rol Administrador

El administrador (IslaTransfers) puede:

- Autenticarse en el sistema.
- Consultar y gestionar:
  - **Reservas** de toda la plataforma.
  - **Usuarios** (viajeros, hoteles).
  - **Hoteles**, **vehículos** y **tarifas (precios)**.
- Ver paneles y listados agregados:
  - Reservas por hotel.
  - Cálculo de **comisiones mensuales** a pagar a cada hotel.
- Acceder al **endpoint REST** con información agregada de reservas por zona.

### 2.2. Rol Hotel

Cada hotel dispone de un usuario con rol `hotel` que puede:

- Autenticarse en el sistema.
- Ver las **reservas de sus clientes** asociadas a su hotel.
- Crear nuevas reservas:
  - Para un **viajero ya registrado**.
  - Para un **nuevo viajero**, creando automáticamente el usuario viajero con contraseña por defecto.
- Modificar reservas (siempre que no estén realizadas o canceladas).
- Cancelar reservas (con confirmación mediante modal).
- Consultar su propia **comisión** derivada de las reservas realizadas.

### 2.3. Rol Viajero

Los usuarios con rol `viajero` pueden:

- Autenticarse en el sistema.
- Gestionar sus propias reservas:
  - Crear nuevas reservas de transfer:
    - Solo ida.
    - Solo vuelta.
    - Ida y vuelta.
  - Editar reservas (con reglas de negocio: mínimo 48 h antes del servicio).
  - Cancelar reservas (mínimo 48 h antes del servicio; se marcan como `cancelada` en lugar de borrarse).
- Ver el detalle de cada reserva en un **modal** desde el panel de reservas.

---

## 3. Requisitos previos

Para ejecutar el proyecto en local:

- PHP **8.x**
- MySQL **8.x**
- Composer
- Extensiones PHP habituales para Laravel (pdo, mbstring, etc.)

---

## 4. Configuración del entorno (`.env`)

Copiar el fichero de ejemplo:

```bash
cp .env.example .env
```

Editar el fichero `.env` y configurar la conexión a la base de datos MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=islatransfers_p3
DB_USERNAME=TU_USUARIO
DB_PASSWORD=TU_PASSWORD
```

Generar la clave de la aplicación:

```bash
php artisan key:generate
```

---

## 5. Migraciones y seeders

Desde la raíz del proyecto:

```bash
php artisan migrate --seed
```

Este comando:

- Crea la estructura de tablas necesaria.
- Ejecuta todos los **seeders**:
  - Usuarios (admin, hoteles, viajeros).
  - Hoteles y zonas.
  - Vehículos.
  - Tarifas (precios) por hotel y vehículo.
  - Reservas con distintos estados, tipos y fechas.
  - Comisiones por hotel coherentes con las tarifas.

Si se necesita rehacer completamente la base de datos:

```bash
php artisan migrate:fresh --seed
```

> ⚠️ **Advertencia**: `migrate:fresh` elimina todas las tablas antes de volver a crearlas.

---

## 6. Puesta en marcha y acceso al sistema (entorno local)

### 6.1. Levantar el servidor de desarrollo

Desde la raíz del proyecto:

```bash
php artisan serve
```

Por defecto, la aplicación quedará disponible en:

- `http://127.0.0.1:8000`
- o `http://localhost:8000`

### 6.2. Acceso desde el navegador

Abrir el navegador y acceder a:

```text
http://127.0.0.1:8000
```

Se mostrará la pantalla de inicio y el formulario de **login**.

---

## 7. Usuarios de prueba / credenciales

Los usuarios de prueba se crean mediante los **seeders**. Todos ellos comparten la misma **contraseña por defecto**:

- Contraseña por defecto: **`islatransfers`**

Los correos electrónicos concretos (admin, hoteles, viajeros) se definen en:

- `database/seeders/UserSeeder.php`

Ejemplo de estructura (puede variar según la versión final del seeder):

- **Administrador**
  - Email: `admin@islatransfers.test`
  - Contraseña: `admin1234`
  - Rol: `admin`
- **Hoteles**
  - Ejemplo: `hotel@islatransfers.test`
  - Contraseña: `hotel1234`
  - Rol: `hotel`
- **Viajeros**
  - Ejemplo: `viajero@islatransfers.test`
  - Contraseña: `viajero1234`
  - Rol: `viajero`

> Para comprobar qué usuarios existen exactamente, revisar el contenido del seeder de usuarios.

---

## 8. Estructura funcional de la aplicación

### 8.1. Namespaces y controladores

La lógica de controladores se organiza por **módulos de rol**:

- `App\Http\Controllers\Admin\*`
  - `AdminReservaController`, etc.
- `App\Http\Controllers\Hotel\*`
  - `HotelReservaController`, etc.
- `App\Http\Controllers\Viajero\*`
  - `ReservaController`, etc.

Además, se utilizan:

- **Form Requests** para validación:
  - `StoreViajeroReservaRequest`
  - `UpdateViajeroReservaRequest`
- **Middlewares / policies implícitas** mediante:
  - Métodos `isAdmin()`, `isHotel()`, `isViajero()` en el modelo `User`.
  - `abort_unless` para bloquear accesos no autorizados.

### 8.2. Modelos y relaciones

Modelos principales:

- `User`
- `Hotel`
- `Viajero`
- `Vehiculo`
- `TiposReserva`
- `Precio`
- `Reserva`

Ejemplos de relaciones:

- `Hotel` tiene muchas `Reserva` (como hotel destino).
- `Viajero` tiene muchas `Reserva`.
- `Reserva` pertenece a:
  - Un `Viajero` (`id_viajero`).
  - Un `Hotel` destino (`id_hotel_destino`).
  - Un `User` creador (`id_creador`).
  - Un `Vehiculo`.
  - Un `Precio`.
  - Un `TipoReserva`.

### 8.3. Vistas y componentes

Se utiliza Blade con layouts diferenciados:

- `resources/views/layouts/app.blade.php` (usuarios autenticados: hotel, viajero).
- `resources/views/layouts/admin.blade.php` o un componente `x-admin-layout` para el panel de administración.

Componentes reutilizables:

- `x-app-layout`, `x-admin-layout`
- Inputs: `x-input-label`, `x-text-input`, `x-primary-button`, etc.
- Parciales para mensajes flash: `layouts.partials.flash-messages`

---

## 9. Reglas de negocio principales

### 9.1. Tipos de reserva

- **Solo ida**
- **Solo vuelta**
- **Ida y vuelta**

Para cada tipo:

- Se muestran/ocultan dinámicamente los bloques de formulario:
  - Datos de llegada (ida).
  - Datos de salida (vuelta).
- Se rellena únicamente la parte de la reserva que aplique.

### 9.2. Reglas para viajeros

- Las reservas deben realizarse con **al menos 48 h de antelación**:
  - Se calcula con `Carbon::diffInHours()` entre `now()` y la fecha/hora del servicio.
- Las reservas **no pueden modificarse** si:
  - Están en estado `cancelada` o `realizada`.
  - Falta menos de 48 h para el servicio.
- La **cancelación** de una reserva:
  - No elimina el registro de la base de datos.
  - Marca la reserva como `cancelada`, actualiza `fecha_modificacion` y `id_modificador`.

### 9.3. Reglas para hoteles

- No se aplica la regla de las 48 h (pueden gestionar reservas más cercanas).
- Deben existir **tarifas (precios)** para la combinación **hotel + vehículo**.
- La creación de una reserva permite:
  - Seleccionar un **viajero existente**, o
  - Crear un **nuevo viajero**, generando también el usuario viajero con contraseña por defecto.
- La **cancelación** desde el panel del hotel:
  - Se realiza mediante un **modal de confirmación**.
  - Cambia el estado a `cancelada` si está en un estado cancelable.

### 9.4. Reglas de comisiones

- Cada hotel tiene un **porcentaje de comisión**.
- La comisión se calcula sobre el **precio base** de la reserva:
  - En reservas `ida y vuelta` se aplica un factor 2 sobre la tarifa base.
- Los importes se almacenan en campos específicos de la reserva:
  - `comision_porcentaje`
  - `comision_importe`
- El panel de administración permite:
  - Ver el **total de comisiones** por hotel y por mes.
  - Listar reservas realizadas por cada hotel como soporte al cálculo.

---

## 10. Seeders y datos de prueba

Los seeders generan un escenario realista:

- Hoteles con zonas asignadas y comisiones configuradas.
- Vehículos con distintas capacidades (plazas).
- Tarifa para cada combinación hotel + vehículo:
  - Se garantiza que todos los hoteles (excepto el primer vehículo, según requisito) tengan tarifas disponibles.
- **Reservas**:
  - 250 reservas generadas automáticamente.
  - Distribución equilibrada:
    - 1/3 solo ida.
    - 1/3 solo vuelta.
    - 1/3 ida y vuelta.
  - Rango de fechas de reserva:
    - Entre 1 de octubre de 2025 y 30 de marzo de 2026.
  - Estados según rango de fechas:
    - Reservas antes del 1 de enero de 2026 → solo `cancelada` o `realizada`.
    - Reservas a partir del 31 de diciembre de 2025 → solo `pendiente` o `confirmada`.
  - Todos los viajeros y todos los hoteles tienen **reservas realizadas** para poder probar los distintos listados y paneles.
  - Las reservas se crean con diferentes **creadores**:
    - Administrador.
    - Hoteles.
    - Viajeros.

---

## 11. WebService REST de reservas por zona

Se ha implementado un endpoint REST que devuelve un **JSON** con información agregada de reservas por zona de la isla. Cada hotel pertenece a una zona, y las reservas se agrupan por la zona del hotel destino.

### 11.1. Endpoint

```http
GET /api/reservas/zonas
```

(La ruta exacta puede variar según la configuración de `routes/api.php`.)

### 11.2. Respuesta

La respuesta es un array de objetos con:

- `zona` – Nombre de la zona.
- `total_reservas` – Número total de reservas realizadas a esa zona.
- `porcentaje` – Porcentaje respecto al total de reservas.

Ejemplo de respuesta:

```json
[
  {
    "zona": "Zona Norte",
    "total_reservas": 120,
    "porcentaje": 48.0
  },
  {
    "zona": "Zona Sur",
    "total_reservas": 80,
    "porcentaje": 32.0
  },
  {
    "zona": "Zona Este",
    "total_reservas": 50,
    "porcentaje": 20.0
  }
]
```

Este endpoint se utilizará en el **Producto 4** para mostrar un dashboard/ listado de reservas por zonas.

---

## 12. Despliegue en AWS (resumen)

El proyecto ha sido desplegado en un servidor **AWS** (instancia Linux) con:

- PHP y extensiones necesarias para Laravel.
- Servidor web (Nginx/Apache) configurado para apuntar al directorio `producto3/public/`.
- Base de datos MySQL accesible desde el servidor.
- Variables de entorno (`.env`) específicas para producción:
  - Credenciales de BD.
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL` apuntando al dominio/URL de AWS.

La principal dificultad fue **adaptar el proyecto del entorno local al servidor AWS** (configuración de PHP, permisos de escritura, directorios `storage` y `bootstrap/cache`, y acceso a la base de datos remota).

---

## 13. Notas para la corrección

- El sistema está preparado para permitir el acceso con los tres roles (**admin**, **hotel**, **viajero**); la experiencia de uso, los menús y los paneles cambian según el rol.
- Se han implementado:
  - Reglas de negocio específicas (48 h, estados, comisiones, tipos de reserva).
  - Modales para:
    - Ver detalle de reservas.
    - Confirmar la cancelación (viajero, hotel, admin según el caso).
- El WebService REST está listo para ser consumido en el siguiente producto (Producto 4) para crear dashboards o listados por zonas.

---

## 14. Licencia

Proyecto desarrollado como parte de un trabajo académico. Uso limitado al ámbito docente y evaluativo.
