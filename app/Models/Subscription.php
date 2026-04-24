<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// El modelo Subscription representa las suscripciones que los usuarios pueden tener para recibir notificaciones sobre incidentes relacionados con servicios específicos en la aplicación.
class Subscription extends Model
{
    /**
     * Los atributos fillable permiten la asignación masiva de los campos especificados, incluyendo 'service_id', 'email', 'token' y 'active'. Esto facilita la creación y actualización de suscripciones a través de métodos como create() o update() sin tener que asignar cada atributo individualmente.
     */
    protected $fillable = [
        'service_id',
        'email',
        'token',
        'active',
    ];

    /**
     * método service: Define una relación de muchos a uno entre el modelo Subscription y el modelo Service, indicando
     * que una suscripción pertenece a un servicio específico. Esta relación permite acceder al servicio asociado a una
     * suscripción determinada, lo que es útil para gestionar las notificaciones relacionadas con los incidentes de ese servicio.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @see Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}