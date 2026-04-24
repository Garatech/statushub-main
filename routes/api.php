<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\IncidentUpdateController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

// -------------------------
// Rutas públicas
// -------------------------

// Estado global del sistema
Route::get('/status', [StatusController::class, 'index']);

// Autenticación
Route::post('/auth/login', [AuthController::class, 'login']);

// Consulta de servicios e incidentes
Route::get('/services',             [ServiceController::class, 'index']);
Route::get('/services/{service}',   [ServiceController::class, 'show']);
Route::get('/incidents',            [IncidentController::class, 'index']);
Route::get('/incidents/{incident}', [IncidentController::class, 'show']);
Route::get('/incidents/{incident}/updates', [IncidentUpdateController::class, 'index']);

// Suscripciones
Route::post('/subscriptions',          [SubscriptionController::class, 'store']);
Route::delete('/subscriptions/{token}', [SubscriptionController::class, 'destroy']);

// -------------------------
// Rutas protegidas (requieren token)
// -------------------------
// Todas las rutas dentro de este grupo requieren autenticación mediante Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Autenticación
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // -------------------------
    // Rutas exclusivas de administrador
    // -------------------------
    Route::middleware('admin')->group(function () {

        // Servicios
        Route::put('/services/{service}/status', [ServiceController::class, 'updateStatus']);

        // Incidentes
        Route::post('/incidents',              [IncidentController::class, 'store']);
        Route::put('/incidents/{incident}',    [IncidentController::class, 'update']);
        Route::delete('/incidents/{incident}', [IncidentController::class, 'destroy']);

        // Actualizaciones de incidentes
        Route::post('/incidents/{incident}/updates', [IncidentUpdateController::class, 'store']);
    });
});