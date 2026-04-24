<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// StoreIncidentUpdateRequest es una clase de solicitud personalizada que extiende FormRequest para validar las solicitudes de actualización de incidentes. Solo los usuarios con permisos de administrador pueden realizar esta acción, y se definen reglas de validación para los campos 'message' y 'status'.
class StoreIncidentUpdateRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud. En este caso, solo los usuarios con
     * permisos de administrador pueden realizar la acción de actualizar un incidente, por lo que se verifica si
     *  el usuario autenticado tiene el rol de administrador utilizando el método isAdmin() del modelo User.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud. En este caso, se requiere que el campo 'message' 
     *sea una cadena de texto y que el campo 'status' sea uno de los valores permitidos: 'investigating', 'identified'
     * ,'monitoring' o 'resolved'. Estas reglas aseguran que los datos enviados en la solicitud sean válidos antes de 
     * procesar la actualización del incidente.
     */
    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'status'  => 'required|in:investigating,identified,monitoring,resolved',
        ];
    }
}