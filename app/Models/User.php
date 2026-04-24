<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// El modelo User representa a los usuarios de la aplicación, incluyendo sus roles y relaciones con incidentes.
class User extends Authenticatable
{
    // El modelo User utiliza los traits HasApiTokens para la autenticación de API y Notifiable para las notificaciones.
    use HasApiTokens, Notifiable;

    // Los atributos fillable permiten la asignación masiva de los campos especificados, incluyendo el nuevo campo 'role'.
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Los atributos hidden ocultan los campos sensibles como 'password' y 'remember_token' al convertir el modelo a un array o JSON.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * function casts: Define los tipos de datos para los atributos del modelo, asegurando que 'email_verified_at' se trate
     * como una fecha y 'password' se maneje como un campo hash.
     * Esto es importante para garantizar que los datos se procesen correctamente y se mantenga la seguridad de las contraseñas.
     * El campo 'email_verified_at' se convierte automáticamente a una instancia de Carbon (fecha y hora) cuando se accede a él, lo que facilita su manipulación.
     * El campo 'password' se hash automáticamente al asignar un valor, lo que mejora la seguridad al almacenar contraseñas en la base de datos.
     * 
     * 
     * @return array Un array que define los tipos de datos para los atributos del modelo.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * método isAdmin: Verifica si el usuario tiene el rol de 'admin', lo que permite controlar el acceso a ciertas
     * funcionalidades o áreas de la aplicación basándose en el rol del usuario.
     * 
     * @return bool Retorna true si el rol del usuario es 'admin', de lo contrario retorna false.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin'; // Verifica si el rol del usuario es 'admin'
    }

    /**
     * método incidents: Define una relación de uno a muchos entre el modelo User y el modelo Incident, indicando que
     * un usuario puede tener múltiples incidentes asociados.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Retorna la relación de uno a muchos entre User e Incident.
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class); // Un usuario puede tener muchos incidentes
    }
}