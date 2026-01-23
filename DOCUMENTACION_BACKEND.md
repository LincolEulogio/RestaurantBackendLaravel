# Documentación del Backend del Sistema de Restaurante (Laravel)

Este documento detalla los procesos, la arquitectura y el funcionamiento interno del backend desarrollado en Laravel. El sistema está diseñado para gestionar de manera integral un restaurante, permitiendo pedidos desde múltiples fuentes, control de cocina, facturación y administración.

---

## 1. Arquitectura General y Módulos Core

El sistema utiliza una arquitectura **MVC (Modelo-Vista-Controlador)** estándar de Laravel, con una separación clara entre las rutas de la plataforma administrativa (`routes/web.php`) y los servicios consumidos por el frontend/aplicaciones móviles (`routes/api.php`).

### Módulos Principales:

- **Módulo de Pedidos (Orders):** El corazón del sistema, que centraliza pedidos de Delivery, Meseros y QR.
- **Módulo de Cocina (KDS - Kitchen Display System):** Interfaz para los cocineros.
- **Módulo de Facturación (Billing):** Procesamiento de pagos y cierre de pedidos.
- **Módulo de Reservas:** Gestión de mesas y disponibilidad.
- **Módulo de Inventario y Menú:** Control de productos, categorías y stock.
- **Módulo de Seguridad (RBAC):** Control de acceso basado en roles y permisos.

---

## 2. Flujo Detallado de Pedidos

El sistema admite tres fuentes principales de pedidos, cada una con un flujo de inicio distinto pero que convergen en el mismo proceso de preparación.

### A. Pedidos Online (Frontend/Web)

1. **Entrada:** El cliente realiza el pedido desde la web pública.
2. **Backend:** Se llama a `App\Http\Controllers\Api\OrderController@store`.
3. **Proceso:**
    - Se valida la información del cliente y los productos.
    - Se crea el registro en `orders` con `order_source = 'web'` o `'online'`.
    - Se calculan totales, impuestos y cargos de envío.
    - **Notificación:** Se dispara una alerta (`NewOrderAlert`) a los usuarios con permiso de pedidos.

### B. Pedidos por Mesero (App Waiter)

1. **Entrada:** El mesero usa la aplicación móvil para tomar el pedido en la mesa.
2. **Backend:** Involucra a `App\Http\Controllers\Api\WaiterOrderController`.
3. **Proceso:**
    - Se abre una **Sesión de Mesa** (`TableSession`) si no existe.
    - Se asocia el pedido a un `waiter_id` y a un `table_id`.
    - El estado de la mesa cambia a `occupied` (ocupado).

### C. Pedidos por QR (Autoservicio)

1. **Entrada:** El cliente escanea un QR en la mesa.
2. **Backend:** Gestionado por `App\Http\Controllers\Api\QROrderController`.
3. **Proceso:**
    - Se verifica el `qr_code` de la mesa.
    - El cliente envía su pedido, el cual se marca como `order_source = 'qr_self_service'`.
    - Permite funciones adicionales como "Llamar al mesero" o "Solicitar cuenta".

---

## 3. Estados del Pedido (Ciclo de Vida)

Todos los pedidos siguen una máquina de estados estricta gestionada en `Order.php`:

| Estado      | Descripción                                        | Responsable         |
| :---------- | :------------------------------------------------- | :------------------ |
| `pending`   | Pedido recién creado, en espera de confirmación.   | Sistema / Admin     |
| `confirmed` | El pedido ha sido aceptado.                        | Admin / Cocina      |
| `preparing` | El pedido está siendo cocinado.                    | Cocina              |
| `ready`     | El plato está listo para ser servido o enviado.    | Cocina              |
| `delivered` | El pedido ha sido entregado y cobrado.             | Mesero / Motorizado |
| `cancelled` | Pedido anulado (con registro de notas del porqué). | Admin               |

**Historial de Estados:** Cada cambio se guarda en la tabla `order_status_history`, permitiendo auditar quién cambió el estado y en qué momento.

**Lógica de Inventario (OrderObserver):** El sistema descuenta automáticamente los ingredientes del inventario cuando un pedido pasa a estado `preparing` o `ready`. Esta lógica está centralizada en `App\Observers\OrderObserver` para garantizar la consistencia sin importar la fuente del pedido.

---

## 4. Proceso de Cocina (KDS) y Tiempo Real

El controlador `KitchenController` maneja la lógica de visualización, apoyado por WebSockets:

1. **Tiempo Real:** Se utiliza el evento `App\Events\OrderPlaced` (broadcast) para notificar a la cocina inmediatamente cuando llega un pedido nuevo.
2. **Acción:** El cocinero puede "Confirmar", "Empezar preparación" o "Marcar como Listo".
3. **Prioridad:** Los pedidos se ordenan por fecha de creación (FIFO).

---

## 5. Proceso de Facturación y Caja

Gestionado por `BillingController`, el proceso se diferencia según el rol del usuario:

- **Cajeros (`cashier`):** Ven todos los pedidos "Presenciales" (Meseros, QR, Comedor).
- **Delivery:** Solo ven pedidos de la web/online.

### Pasos para el Cobro:

1. Solo los pedidos en estado `ready` pueden ser cobrados.
2. El cajero selecciona el método de pago (`cash`, `card`, `yape`, `plin`).
3. Se registra el monto recibido y se calcula el cambio.
4. Al marcar como pagado, el estado del pedido cambia automáticamente a `delivered` y se registra la venta en el historial financiero.

---

## 6. Gestión de Administración y Mantenimiento

### Roles y Permisos

El sistema utiliza un middleware personalizado `permission:nombre_permiso`. Los principales permisos son:

- `orders`, `menu`, `inventory`, `reports`, `kitchen`, `billing`, `settings`.

### Servicios del Sistema

- **Imágenes (Cloudinary):** Gestión de multimedia en la nube vía `CloudinaryService`.
- **Impresión (PrintService):** El sistema está preparado para impresión térmica (ESC/POS).
    - _Nota:_ Actualmente en modo "Simulación/Log" para evitar errores 500 por dependencias faltantes en el entorno local. Los tickets se registran en los logs de Laravel.

---

## 7. Esquema de Base de Datos (Tablas Clave)

1. **`users`**, **`products`**, **`orders`**, **`order_items`**, **`tables` / `table_sessions`**, **`reservations`**, **`promotions`**.

---

## 8. Seguridad, API y CORS

- **Autenticación:**
    - **Web:** Laravel Breeze.
    - **API:** **Laravel Sanctum** (Bearer Tokens para App Mesero/QR).
- **CORS (Middleware Personalizado):**
    - Se ha implementado un middleware robusto (`App\Http\Middleware\Cors`) que garantiza la conectividad entre el puerto 3000 (Frontend) y 8000 (Backend).
    - **Control de Errores:** El middleware captura excepciones críticas para asegurar que incluso en un Error 500, el navegador reciba las cabeceras CORS, permitiendo al frontend leer el detalle del fallo.
    - **Prevención de Duplicados:** Implementa lógica para evitar cabeceras duplicadas que bloqueen al navegador.

---

## 9. Diagnóstico y Robustez

Para facilitar el mantenimiento, se han implementado herramientas de diagnóstico profundo:

- **`debug_log.json`:** En la carpeta `public`, el sistema registra el rastro (trace) completo de cualquier fallo en la creación de pedidos.
- **Detección de Fallos en Middleware:** Los controladores (`OrderController`, `WaiterOrderController`) están protegiendo la transacción de base de datos (`DB::beginTransaction`) y capturando cualquier `Throwable` para loguear errores detallados en `storage/logs/laravel.log`.

---

**Nota:** Esta documentación se actualiza con cada cambio crítico en la arquitectura del servidor.
