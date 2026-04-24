<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Service;

class StatusController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/status",
     *     summary="Estado global del sistema",
     *     description="Devuelve el estado global del sistema, el número de incidentes activos y la lista de todos los servicios con su estado actual.",
     *     tags={"Estado"},
     *     @OA\Response(
     *         response=200,
     *         description="Estado del sistema obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="global_status", type="string", example="operational"),
     *             @OA\Property(property="active_incidents", type="integer", example=2),
     *             @OA\Property(property="services", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="API de Usuarios"),
     *                     @OA\Property(property="slug", type="string", example="api-usuarios"),
     *                     @OA\Property(property="status", type="string", example="operational")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $services = Service::all();

        $degradedCount  = $services->whereIn('status', ['degraded', 'partial_outage', 'major_outage'])->count();
        $criticalCount  = $services->where('status', 'major_outage')->count();

        if ($criticalCount > 0) {
            $globalStatus = 'major_outage';
        } elseif ($degradedCount > 0) {
            $globalStatus = 'degraded';
        } else {
            $globalStatus = 'operational';
        }

        $activeIncidents = Incident::where('status', '!=', 'resolved')->count();

        return response()->json([
            'global_status'    => $globalStatus,
            'active_incidents' => $activeIncidents,
            'services'         => $services,
        ]);
    }
}