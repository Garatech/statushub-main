<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Esta migración agrega una columna 'role' a la tabla 'users' para definir el rol de cada usuario (admin o user).
return new class extends Migration
{
    // En el método 'up', se agrega la columna 'role' a la tabla 'users' con un valor predeterminado de 'user'.
    public function up(): void
    {
        // Se agrega la columna 'role' a la tabla 'users' con un tipo de dato ENUM que permite los valores 'admin' y 'user', y se establece 'user' como valor predeterminado.
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('email');
        });
    }

    // En el método 'down', se elimina la columna 'role' de la tabla 'users' para revertir los cambios realizados en el método 'up'.
    public function down(): void
    {
        // Se elimina la columna 'role' de la tabla 'users'.
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};