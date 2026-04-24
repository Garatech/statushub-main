<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentRequest;
use App\Models\Incident;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/incidents",
     *     summary="Lista de incidentes",
     *     description="Devuelve la lista paginada de incidentes. Permite filtrar por servicio y estado.",
     *     tags={"Incidentes"},
     *     @OA\Parameter(
     *         name="service_id",
     *         in="query",
     *         required=false,
     *         description="Filtrar por ID de servicio",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filtrar por estado del incidente",
     *         @OA\Schema(type="string", enum={"investigating","identified","monitoring","resolved"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de incidentes obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="total", type="integer", example=10),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Latencia elevada en API de Usuarios"),
     *                     @OA\Property(property="status", type="string", example="investigating"),
     *                     @OA\Property(property="impact", type="string", example="minor"),
     *                     @OA\Property(property="resolved_at", type="string", nullable=true, example=null)
     *                 )
     *             )
     *         )
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
     * @OA\Get(
     *     path="/api/incidents/{incident}",
     *     summary="Detalle de un incidente",
     *     description="Devuelve los detalles de un incidente específico incluyendo su servicio y actualizaciones.",
     *     tags={"Incidentes"},
     *     @OA\Parameter(
     *         name="incident",
     *         in="path",
     *         required=true,
     *         description="ID del incidente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle del incidente obtenido correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Incidente no encontrado"
     *     )
     * )
     */
    public function show(Incident $incident)
    {
        $incident->load(['service', 'user', 'updates.user']);

        return response()->json($incident);
    }

    /**
     * @OA\Post(
     *     path="/api/incidents",
     *     summary="Crear un incidente",
     *     description="Crea un nuevo incidente y actualiza el estado del servicio según el impacto. Solo administradores.",
     *     tags={"Incidentes"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"service_id","title","impact","status"},
     *             @OA\Property(property="service_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Latencia elevada en API de Usuarios"),
     *             @OA\Property(property="description", type="string", example="Se detecta un aumento inusual en los tiempos de respuesta."),
     *             @OA\Property(property="impact", type="string", enum={"minor","major","critical"}, example="minor"),
     *             @OA\Property(property="status", type="string", enum={"investigating","identified","monitoring","resolved"}, example="investigating")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Incidente creado correctamente"
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
    public function store(StoreIncidentRequest $request)
    {
        $data = $request->validated();

        $incident = DB::transaction(function () use ($data, $request) {
            $incident = Incident::create([
                'service_id'  => $data['service_id'],
                'user_id'     => $request->user()->id,
                'title'       => $data['title'],
                'description' => $data['description'] ?? null,
                'impact'      => $data['impact'],
                'status'      => $data['status'],
            ]);

            $serviceStatus = match($data['impact']) {
                'minor'    => 'degraded',
                'major'    => 'partial_outage',
                'critical' => 'major_outage',
            };

            Service::where('id', $data['service_id'])
                ->update(['status' => $serviceStatus]);

            return $incident;
        });

        $incident->load('service');

        return response()->json($incident, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/incidents/{incident}",
     *     summary="Actualizar un incidente",
     *     description="Actualiza un incidente existente. Si se marca como resuelto, restaura el servicio si no hay otros incidentes activos. Solo administradores.",
     *     tags={"Incidentes"},
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
     *             @OA\Property(property="title", type="string", example="Latencia elevada en API de Usuarios"),
     *             @OA\Property(property="description", type="string", example="Se detecta un aumento inusual en los tiempos de respuesta."),
     *             @OA\Property(property="impact", type="string", enum={"minor","major","critical"}, example="minor"),
     *             @OA\Property(property="status", type="string", enum={"investigating","identified","monitoring","resolved"}, example="resolved")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incidente actualizado correctamente"
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
    public function update(UpdateIncidentRequest $request, Incident $incident)
    {
        $data = $request->validated();

        $incident = DB::transaction(function () use ($data, $incident) {
            if (isset($data['status']) && $data['status'] === 'resolved') {
                $data['resolved_at'] = now();

                $activeIncidents = Incident::where('service_id', $incident->service_id)
                    ->where('id', '!=', $incident->id)
                    ->where('status', '!=', 'resolved')
                    ->count();

                if ($activeIncidents === 0) {
                    Service::where('id', $incident->service_id)
                        ->update(['status' => 'operational']);
                }
            }

            $incident->update($data);

            return $incident;
        });

        $incident->load('service');

        return response()->json($incident);
    }

    /**
     * @OA\Delete(
     *     path="/api/incidents/{incident}",
     *     summary="Eliminar un incidente",
     *     description="Elimina un incidente y restaura el servicio a operational si no quedan incidentes activos. Solo administradores.",
     *     tags={"Incidentes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="incident",
     *         in="path",
     *         required=true,
     *         description="ID del incidente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incidente eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Incidente eliminado correctamente.")
     *         )
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
    public function destroy(Incident $incident)
    {
        DB::transaction(function () use ($incident) {
            $activeIncidents = Incident::where('service_id', $incident->service_id)
                ->where('id', '!=', $incident->id)
                ->where('status', '!=', 'resolved')
                ->count();

            if ($activeIncidents === 0) {
                Service::where('id', $incident->service_id)
                    ->update(['status' => 'operational']);
            }

            $incident->delete();
        });

        return response()->json([
            'message' => 'Incidente eliminado correctamente.',
        ]);
    }
}