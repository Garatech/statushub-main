<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// El modelo Incident representa los incidentes que pueden ocurrir en los servicios de la aplicación, incluyendo detalles como el título, descripción, estado, impacto y la fecha de resolución.
class Incident extends Model
{
    // Los atributos fillable permiten la asignación masiva de los campos especificados, incluyendo
    // 'service_id', 'user_id', 'title', 'description', 'status', 'impact' y 'resolved_at'.
    protected $fillable = [
        'service_id',
        'user_id',
        'title',
        'description',
        'status',
        'impact',
        'resolved_at',
    ];

    /**
     * Los casts definen cómo se deben convertir los atributos a tipos específicos, como 'datetime' para 'resolved_at'.
     * Esto permite que el atributo 'resolved_at' se maneje automáticamente como una instancia de Carbon (fecha y hora)
     * cuando se accede a él o se asigna un valor.
     * 
     * En este caso, el atributo 'resolved_at' se convertirá automáticamente a un objeto de fecha y hora cuando se
     * acceda a él, lo que facilita su manipulación y formateo en la aplicación.
     * 
     * @return array
     */
    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * método service: Define una relación de muchos a uno entre el modelo Incident y el modelo Service, indicando que un incidente
     * pertenece a un servicio específico. Esta relación permite acceder al servicio asociado a un incidente determinado.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * método user: Define una relación de muchos a uno entre el modelo Incident y el modelo User, indicando que un incidente
     * pertenece a un usuario específico. Esta relación permite acceder al usuario que reportó o está asociado con un incidente determinado.
     * 
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** 
     * método updates: Define una relación de uno a muchos entre el modelo Incident y el modelo IncidentUpdate, indicando que un incidente
     * puede tener múltiples actualizaciones asociadas. Esta relación permite acceder a las actualizaciones relacionadas con un incidente específico.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function updates()
    {
        return $this->hasMany(IncidentUpdate::class);
    }
}