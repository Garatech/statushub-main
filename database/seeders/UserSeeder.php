<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Seeder para crear usuarios de prueba en la base de datos, incluyendo un administrador y un usuario regular
class UserSeeder extends Seeder
{
    /**
     * Ejecutar el seeder para crear usuarios de prueba en la base de datos
     * Este método se ejecutará cuando se ejecute el comando de seeding y creará dos usuarios: uno con rol de
     * administrador y otro con rol de usuario regular, ambos con contraseñas seguras utilizando Hash::make()
     * 
     * @return void
     */
    public function run(): void
    {
        // Crear un usuario administrador con el correo electrónico
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@statushub.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Crear un usuario regular con el correo electrónico
        User::create([
            'name'     => 'Usuario Demo',
            'email'    => 'user@statushub.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);
    }
}