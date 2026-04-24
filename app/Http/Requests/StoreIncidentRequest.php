<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// StoreIncidentRequest es una clase de solicitud personalizada que extiende FormRequest para validar los datos de entrada al crear un nuevo incidente.
class StoreIncidentRequest extends FormRequest
{
    /**
     * function authorize(): se utiliza para determinar si el usuario que realiza la solicitud tiene permiso para crear un
     * nuevo incidente. En este caso, se verifica si el usuario es un administrador llamando al método isAdmin()
     * en el objeto de usuario autenticado. Si el usuario no es un administrador, la solicitud será rechazada y se
     *  devolverá una respuesta de error 403 Forbidden.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * function rules(): se utiliza para definir las reglas de validación que se aplicarán a los datos de entrada al
     * crear un nuevo incidente.
     * Las reglas especifican que el campo service_id es obligatorio y debe existir en la tabla
     * 
     * @return array services, el campo title es obligatorio, debe ser una cadena de texto y no puede exceder los
     */
    public function rules(): array
    {
        return [
            'service_id'  => 'required|exists:services,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'impact'      => 'required|in:minor,major,critical',
            'status'      => 'required|in:investigating,identified,monitoring,resolved',
        ];
    }
}