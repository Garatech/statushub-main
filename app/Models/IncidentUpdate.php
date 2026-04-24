<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// El modelo IncidentUpdate representa las actualizaciones que se pueden realizar en un incidente, incluyendo el mensaje de actualización y el estado del incidente en ese momento.
class IncidentUpdate extends Model
{
    // Los atributos fillable permiten la asignación masiva de los campos especificados, incluyendo 'incident_id', 'user_id', 'message' y 'status'.
    protected $fillable = [
        'incident_id',
        'user_id',
        'message',
        'status',
    ];

    /**
     * método incident: Define una relación de muchos a uno entre el modelo IncidentUpdate y el modelo Incident, indicando
     * que una actualización de incidente pertenece a un incidente específico. Esta relación permite acceder al incidente
     * asociado a una actualización determinada.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @see Incident
     */
    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * método user: Define una relación de muchos a uno entre el modelo IncidentUpdate y el modelo User, indicando
     * que una actualización de incidente pertenece a un usuario específico. Esta relación permite acceder al usuario
     * que realizó la actualización de incidente.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @see User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}