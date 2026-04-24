<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Esta migración crea la tabla 'services' para almacenar información sobre los servicios monitoreados, incluyendo su nombre, slug, descripción y estado.
return new class extends Migration
{
    // En el método 'up', se define la estructura de la tabla 'services' con las columnas necesarias para almacenar la información de cada servicio.
    public function up(): void
    {
        /** Se crea la tabla 'services' con las siguientes columnas:
         * - 'id': Identificador único de cada servicio (clave primaria).
         * - 'name': Nombre del servicio.
         * - 'slug': Identificador único legible para URLs, utilizado para referenciar el
         *  servicio de manera amigable.
         * - 'description': Descripción opcional del servicio.
         * - 'status': Estado actual del servicio, con valores posibles como 'operational', 'degraded', 'partial_outage' y 'major_outage'.
         * - 'timestamps': Columnas 'created_at' y 'updated_at' para registrar
        */
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['operational', 'degraded', 'partial_outage', 'major_outage'])->default('operational');
            $table->timestamps();
        });
    }

    // En el método 'down', se elimina la tabla 'services' para revertir los cambios realizados en el método 'up'.
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};