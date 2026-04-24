<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="StatusHub API",
 *     version="1.0.0",
 *     description="API REST para monitorización de servicios IT en tiempo real.",
 *     @OA\Contact(
 *         email="admin@statushub.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local (Docker)"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Introduce el token obtenido en /api/auth/login"
 * )
 *
 * @OA\PathItem(path="/api")
 */
abstract class Controller
{
    //
}