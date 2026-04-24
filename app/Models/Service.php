<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// El modelo Service representa los servicios que pueden estar asociados a incidentes y suscripciones en la aplicación.
class Service extends Model
{
    // Los atributos fillable permiten la asignación masiva de los campos especificados, incluyendo 'name', 'slug', 'description' y 'status'.
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    /**
     * método incidents: Define una relación de uno a muchos entre el modelo Service y el modelo Incident, indicando que
     * un servicio puede tener múltiples incidentes asociados. Esta relación permite acceder a los incidentes relacionados
     * con un servicio específico.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @see Incident
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * método subscriptions: Define una relación de uno a muchos entre el modelo Service y el modelo Subscription, indicando
     * que un servicio puede tener múltiples suscripciones asociadas. Esta relación permite acceder a las suscripciones
     * relacionadas con un servicio específico.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @see Subscription
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}