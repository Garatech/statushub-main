<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//  UpdateServiceStatusRequest es una clase de solicitud personalizada que extiende FormRequest para validar las solicitudes de actualización del estado del servicio. Solo los usuarios con permisos de administrador pueden realizar esta acción, y se define una regla de validación para el campo 'status' que requiere que sea uno de los valores permitidos: 'operational', 'degraded', 'partial_outage' o 'major_outage'.
class UpdateServiceStatusRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud. En este caso, solo los usuarios con
     * permisos de administrador pueden realizar la acción de actualizar el estado del servicio, por lo que se verifica si
     *  el usuario autenticado tiene el rol de administrador utilizando el método isAdmin() del modelo User.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud. En este caso, se requiere que el campo 'status' sea
     * uno de los valores permitidos: 'operational', 'degraded', 'partial_outage' o 'major_outage'. Esta regla asegura
     * que el estado del servicio enviado en la solicitud sea válido antes de procesar la actualización.
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:operational,degraded,partial_outage,major_outage',
        ];
    }
}
