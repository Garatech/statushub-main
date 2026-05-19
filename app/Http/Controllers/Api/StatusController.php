<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Service;
use App\Support\ServiceStatusPresenter;

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
        $services = Service::with([
            'snapshots' => fn ($query) => $query
                ->where('recorded_on', '>=', now()->subDays(29)->toDateString())
                ->orderBy('recorded_on'),
            'incidents' => fn ($query) => $query->orderBy('created_at', 'desc'),
        ])->get();

        $degradedCount  = $services->whereIn('status', ['degraded', 'partial_outage'])->count();
        $criticalCount  = $services->where('status', 'major_outage')->count();

        if ($criticalCount > 0) {
            $globalStatus = 'outage';
        } elseif ($degradedCount > 0) {
            $globalStatus = 'degraded';
        } else {
            $globalStatus = 'operational';
        }

        $activeIncidents = Incident::where('status', '!=', 'resolved')->count();
        $servicesPayload = $services
            ->map(fn ($service) => ServiceStatusPresenter::presentService($service))
            ->values();

        return response()->json([
            'global_status'    => $globalStatus,
            'active_incidents' => $activeIncidents,
            'metrics'          => ServiceStatusPresenter::presentGlobalMetrics($servicesPayload),
            'services'         => $servicesPayload,
        ]);
    }
}
