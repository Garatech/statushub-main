<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentUpdateRequest;
use App\Models\Incident;
use App\Models\IncidentUpdate;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IncidentUpdateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/incidents/{incident}/updates",
     *     summary="Lista de actualizaciones de un incidente",
     *     description="Devuelve todas las actualizaciones de un incidente específico.",
     *     tags={"Actualizaciones"},
     *     @OA\Parameter(
     *         name="incident",
     *         in="path",
     *         required=true,
     *         description="ID del incidente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de actualizaciones obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Incidente no encontrado"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Incident::with(['service', 'updates'])
            ->orderBy('created_at', 'desc');

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $incidents = $query->paginate(10);

        return response()->json($incidents);
    }

    /**
     * @OA\Post(
     *     path="/api/incidents/{incident}/updates",
     *     summary="Añadir actualización a un incidente",
     *     description="Crea una nueva actualización para un incidente y sincroniza su estado. Si se marca como resuelto, restaura el servicio si no quedan incidentes activos. Solo administradores.",
     *     tags={"Actualizaciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="incident",
     *         in="path",
     *         required=true,
     *         description="ID del incidente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message","status"},
     *             @OA\Property(property="message", type="string", example="Hemos identificado la causa raíz."),
     *             @OA\Property(property="status", type="string", enum={"investigating","identified","monitoring","resolved"}, example="identified")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Actualización creada correctamente"
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
    public function store(StoreIncidentUpdateRequest $request, Incident $incident)
    {
        $data = $request->validated();

        $update = DB::transaction(function () use ($data, $request, $incident) {
            $update = IncidentUpdate::create([
                'incident_id' => $incident->id,
                'user_id'     => $request->user()->id,
                'message'     => $data['message'],
                'status'      => $data['status'],
            ]);

            $incident->update(['status' => $data['status']]);

            if ($data['status'] === 'resolved') {
                $incident->update(['resolved_at' => now()]);

                $activeIncidents = Incident::where('service_id', $incident->service_id)
                    ->where('id', '!=', $incident->id)
                    ->where('status', '!=', 'resolved')
                    ->count();

                if ($activeIncidents === 0) {
                    Service::where('id', $incident->service_id)
                        ->update(['status' => 'operational']);
                }
            }

            return $update;
        });

        $update->load('user');

        return response()->json($update, 201);
    }
}