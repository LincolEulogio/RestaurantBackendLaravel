<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="RestaurantApp API",
 *     version="1.0.0",
 *     description="API completa para sistema de gestión de restaurante con órdenes online, presenciales, QR self-service, reservaciones y pagos",
 *     @OA\Contact(
 *         email="support@restaurantapp.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Enter token in format: Bearer {token}"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints de autenticación"
 * )
 *
 * @OA\Tag(
 *     name="Products",
 *     description="Gestión de productos y menú"
 * )
 *
 * @OA\Tag(
 *     name="Orders",
 *     description="Gestión de órdenes (web, presencial, QR)"
 * )
 *
 * @OA\Tag(
 *     name="Reservations",
 *     description="Sistema de reservaciones"
 * )
 *
 * @OA\Tag(
 *     name="Payments",
 *     description="Procesamiento de pagos con Culqi"
 * )
 *
 * @OA\Tag(
 *     name="Waiter",
 *     description="Endpoints para meseros"
 * )
 *
 * @OA\Tag(
 *     name="QR Self-Service",
 *     description="Endpoints para servicio QR autoservicio"
 * )
 */
abstract class Controller
{
    //
}
