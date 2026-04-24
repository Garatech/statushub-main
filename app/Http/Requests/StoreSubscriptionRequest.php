<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Clase que maneja la validación de las solicitudes para crear una nueva suscripción a un servicio.
class StoreSubscriptionRequest extends FormRequest
{
    /** 
    * function authorize: Permite que cualquier usuario pueda realizar esta solicitud, ya que no se requiere autenticación
    * para suscribirse a un servicio.
    * @return bool
    */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * function rules: Define las reglas de validación para los campos 'service_id' y 'email'.
     * @return array
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'email'      => 'required|email|max:255',
        ];
    }
}