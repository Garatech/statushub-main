<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

// Seeder para poblar la tabla de servicios con datos iniciales para pruebas y desarrollo
class ServiceSeeder extends Seeder
{
    // Método para ejecutar el seeding de la tabla de servicios con un conjunto de datos predefinidos
    public function run(): void
    {
        // Definir un array de servicios con sus respectivos atributos como nombre, slug, descripción y estado
        $services = [
            [
                'name'        => 'API de Usuarios',
                'slug'        => 'api-usuarios',
                'description' => 'Gestión de autenticación y perfiles de usuario.',
                'status'      => 'operational',
            ],
            [
                'name'        => 'Base de Datos',
                'slug'        => 'base-de-datos',
                'description' => 'Almacenamiento y recuperación de datos.',
                'status'      => 'operational',
            ],
            [
                'name'        => 'CDN de Assets',
                'slug'        => 'cdn-assets',
                'description' => 'Distribución de archivos estáticos.',
                'status'      => 'operational',
            ],
            [
                'name'        => 'Servicios de Caché',
                'slug'        => 'cache',
                'description' => 'Almacenamiento temporal para mejorar el rendimiento.',
                'status'      => 'operational',
            ],
            [
                'name'        => 'Terminales de Pago',
                'slug'        => 'terminales-pago',
                'description' => 'Procesamiento de transacciones económicas.',
                'status'      => 'operational',
            ],
            [
                'name'        => 'Servicios de Email',
                'slug'        => 'email',
                'description' => 'Envío y recepción de correos electrónicos.',
                'status'      => 'operational',
            ],
        ];

        // Iterar sobre el array de servicios y crear un registro en la base de datos para cada uno utilizando el modelo Service
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}