<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Esta clase de solicitud se utiliza para validar las solicitudes de actualización de incidentes. Solo los usuarios con permisos de administrador pueden realizar esta acción, y se definen reglas de validación para cada campo que se puede actualizar.
class UpdateIncidentRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud. En este caso, solo los usuarios con permisos de
     * administrador pueden actualizar un incidente, por lo que se verifica si el usuario autenticado tiene el rol de
     * administrador.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud. En este caso, se definen reglas para los campos
     * 'title', 'description', 'impact' y 'status'. Cada campo es opcional (usando 'sometimes'), y se especifican los
     * tipos de datos y las restricciones para cada uno. Por ejemplo, 'title' debe ser una cadena de texto con un
     * máximo de 255 caracteres, mientras que 'impact' y 'status' deben ser valores específicos dentro de un conjunto
     * definido.
     */
    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'impact'      => 'sometimes|in:minor,major,critical',
            'status'      => 'sometimes|in:investigating,identified,monitoring,resolved',
        ];
    }
}