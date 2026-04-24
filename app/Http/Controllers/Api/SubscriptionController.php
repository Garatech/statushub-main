<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/subscriptions",
     *     summary="Crear suscripción",
     *     description="Suscribe un email a las notificaciones de un servicio. Devuelve 409 si ya existe una suscripción activa.",
     *     tags={"Suscripciones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"service_id","email"},
     *             @OA\Property(property="service_id", type="integer", example=1),
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Suscripción creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Suscripción creada correctamente."),
     *             @OA\Property(property="subscription", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="service_id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", example="test@example.com"),
     *                 @OA\Property(property="token", type="string", example="YicWR5SiYfdf0FrkjuU1uFYDu2ZAmMbR"),
     *                 @OA\Property(property="active", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Ya existe una suscripción activa para este email y servicio"
     *     )
     * )
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();

        $existing = Subscription::where('service_id', $data['service_id'])
            ->where('email', $data['email'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Ya existe una suscripción activa para este email y servicio.',
            ], 409);
        }

        $subscription = Subscription::create([
            'service_id' => $data['service_id'],
            'email'      => $data['email'],
            'token'      => Str::random(32),
            'active'     => true,
        ]);

        return response()->json([
            'message'      => 'Suscripción creada correctamente.',
            'subscription' => $subscription,
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/subscriptions/{token}",
     *     summary="Cancelar suscripción",
     *     description="Cancela una suscripción utilizando su token único.",
     *     tags={"Suscripciones"},
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true,
     *         description="Token único de la suscripción",
     *         @OA\Schema(type="string", example="YicWR5SiYfdf0FrkjuU1uFYDu2ZAmMbR")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Suscripción cancelada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Suscripción cancelada correctamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Suscripción no encontrada"
     *     )
     * )
     */
    public function destroy(string $token)
    {
        $subscription = Subscription::where('token', $token)->firstOrFail();
        $subscription->delete();

        return response()->json([
            'message' => 'Suscripción cancelada correctamente.',
        ]);
    }
}