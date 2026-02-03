# Correcciones aplicadas – Pedidos y Facturación

## Resumen

Se corrigieron errores y se alineó el flujo de **hacer pedido** entre frontend y backend, y se ajustó el **InvoiceService** para que funcione cuando el pedido no tiene cliente vinculado.

---

## Backend

### 1. **OrderController (API pública – crear pedido)**

- Se asignan al crear el pedido:
  - `order_source` = `'online'` (pedidos desde la web).
  - `payment_status` = `'pending'`.
- Así el pedido queda correctamente tipado para cocina, cobranza y facturación.

### 2. **OrderResource (respuesta del API)**

- Se añadieron **campos planos** compatibles con la interfaz `Order` del frontend:
  - `customer_name`, `customer_lastname`, `customer_email`, `customer_phone`, `delivery_address`
  - `order_type`, `status`, `subtotal`, `tax`, `delivery_fee`, `total`, `notes`, `created_at`
  - `payment_status`, `order_source`
- En **items** se incluyen:
  - `product_id`, `special_instructions`
  - Valores numéricos como `(float)` para evitar tipos incorrectos.
- Se mantienen los objetos anidados (`customer`, `totals`, `status_object`, `dates`) para uso interno/admin.

### 3. **WaiterOrderController y QROrderController**

- Al crear pedidos desde mesero o QR se asignan:
  - `order_type` = `'dine-in'`
  - `payment_status` = `'pending'`
  - `delivery_fee` = `0`
- Evita valores nulos o inconsistentes en listados y reportes.

### 4. **InvoiceService**

- **Pedido sin Customer:**  
  El modelo `Order` no tiene relación `customer`; la factura puede crearse sin `Customer` en BD.
- **createFromOrder:**
  - `customer_id` se resuelve con `resolveCustomerIdFromOrder()` (búsqueda por email o DNI en `customers`).
  - Tipo de comprobante con `determineInvoiceTypeFromOrder()` (usa datos del pedido o del Customer si existe).
- **createInvoiceItemsFromOrder:**
  - Ya no se usan `tax_amount` ni `total` de `OrderItem` (no existen).
  - Se calculan `subtotal`, `igv` (18 %) y `total` por ítem.
- **prepareNubefactData:**
  - Si la factura no tiene `customer`, se usan datos del pedido: `customer_name`, `customer_lastname`, `customer_dni`, `delivery_address`, `customer_email`.
  - Valores por defecto seguros cuando faltan (ej. denominación "Cliente", DNI "00000000").
- **prepareNubefactData** y uso de `order`:
  - Uso de `$order?->` para cuando `invoice->order` sea null.

---

## Frontend

### 1. **createOrder (lib/api/orders.ts)**

- Se definió la interfaz `CreateOrderResponse` con `message` y `order`.
- `createOrder()` ahora:
  - Hace `POST` y recibe `CreateOrderResponse | Order`.
  - Devuelve siempre el **pedido**: `response.order` si existe, sino `response`.
- El tipo de retorno sigue siendo `Promise<Order>` para que el resto del código no cambie.

### 2. **useCartSidebar (hooks/useCartSidebar.ts)**

- Se reemplazó:
  - `const response = await createOrder(orderData); const order = response.order || response;`
- Por:
  - `const order = await createOrder(orderData);`
- El flujo de pago (efectivo, Culqi, etc.) sigue usando `order.id`, `order.order_number` y `customerEmail` sin cambios.

---

## Flujo de pedido (frontend → backend)

1. Usuario llena datos y confirma pago.
2. Frontend llama a `createOrder(orderData)`.
3. Backend valida, crea el pedido con `order_source = 'online'` y `payment_status = 'pending'`.
4. Backend responde `{ message, order }` con `OrderResource`.
5. Frontend recibe en `order` los campos planos (`order_number`, `total`, `status`, etc.) y los usa para éxito, Culqi y almacenamiento local.

---

## Cómo probar

- **Pedido web (delivery/pickup):**  
  Agregar productos al carrito, completar datos, elegir método de pago y confirmar. Debe crearse el pedido y mostrarse el número de orden.
- **Pedido mesero:**  
  Login mesero, mesa, agregar ítems y enviar. Debe crearse el pedido con `order_source = 'waiter'`.
- **Pedido QR:**  
  Escanear QR de mesa, agregar ítems y enviar. Debe crearse con `order_source = 'qr_self_service'`.
- **Facturación:**  
  Con un pedido en estado entregado y pagado, crear factura desde el backend; debe funcionar aunque el pedido no tenga `Customer` en BD (se usan datos del pedido en Nubefact).

---

## Archivos modificados

- `backend/app/Http/Controllers/Api/OrderController.php`
- `backend/app/Http/Resources/OrderResource.php`
- `backend/app/Http/Controllers/Api/WaiterOrderController.php`
- `backend/app/Http/Controllers/Api/QROrderController.php`
- `backend/app/Services/InvoiceService.php`
- `frontend/lib/api/orders.ts`
- `frontend/hooks/useCartSidebar.ts`
