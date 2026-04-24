<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateServiceStatusRequest;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/services",
     *     summary="Lista de servicios",
     *     description="Devuelve la lista de todos los servicios con su estado actual.",
     *     tags={"Servicios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de servicios obtenida correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="API de Usuarios"),
     *                 @OA\Property(property="slug", type="string", example="api-usuarios"),
     *                 @OA\Property(property="description", type="string", example="Gestión de autenticación y perfiles de usuario."),
     *                 @OA\Property(property="status", type="string", example="operational")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $services = Service::all();

        return response()->json($services);
    }

    /**
     * @OA\Get(
     *     path="/api/services/{service}",
     *     summary="Detalle de un servicio",
     *     description="Devuelve los detalles de un servicio específico incluyendo sus incidentes activos.",
     *     tags={"Servicios"},
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         required=true,
     *         description="ID del servicio",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle del servicio obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="API de Usuarios"),
     *             @OA\Property(property="slug", type="string", example="api-usuarios"),
     *             @OA\Property(property="description", type="string", example="Gestión de autenticación y perfiles de usuario."),
     *             @OA\Property(property="status", type="string", example="operational"),
     *             @OA\Property(property="incidents", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Latencia elevada"),
     *                     @OA\Property(property="status", type="string", example="investigating"),
     *                     @OA\Property(property="impact", type="string", example="minor")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servicio no encontrado"
     *     )
     * )
     */
    public function show(Service $service)
    {
        $service->load([
            'incidents' => function ($query) {
                $query->where('status', '!=', 'resolved')
                      ->orderBy('created_at', 'desc');
            }
        ]);

        return response()->json($service);
    }

    /**
     * @OA\Put(
     *     path="/api/services/{service}/status",
     *     summary="Actualizar estado de un servicio",
     *     description="Actualiza manualmente el estado de un servicio. Solo accesible para administradores.",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         required=true,
     *         description="ID del servicio",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="degraded",
     *                 enum={"operational","degraded","partial_outage","major_outage"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     )
     * )
     */
    public function updateStatus(UpdateServiceStatusRequest $request, Service $service)
    {
        $service->update([
            'status' => $request->validated()['status'],
        ]);

        return response()->json($service);
    }
}