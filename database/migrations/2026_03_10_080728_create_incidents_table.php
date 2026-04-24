<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Esta migración crea la tabla 'incidents' para almacenar los incidentes relacionados con los servicios. Cada incidente tiene un título, descripción, estado, impacto y una referencia al servicio y al usuario que lo reportó. Además, se registra la fecha de resolución del incidente.
return new class extends Migration
{
    // Aquí se define la estructura de la tabla 'incidents' con sus respectivas columnas y relaciones. Se establece una relación de clave foránea con las tablas 'services' y 'users', y se definen los campos necesarios para describir un incidente, como el título, descripción, estado, impacto y fecha de resolución.
    public function up(): void
    {
        /**  Crear la tabla 'incidents' con las columnas:
         * - id: Identificador único del incidente.
         * - service_id: Clave foránea que referencia al servicio afectado.
         * - user_id: Clave foránea que referencia al usuario que reportó el incidente.
         * - title: Título del incidente.
         * - description: Descripción detallada del incidente (opcional).
         * - status: Estado actual del incidente (investigating, identified, monitoring, resolved
         * - impact: Nivel de impacto del incidente (minor, major, critical).
         * - resolved_at: Fecha y hora en que se resolvió el incidente (opcional).
        */
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['investigating', 'identified', 'monitoring', 'resolved'])->default('investigating');
            $table->enum('impact', ['minor', 'major', 'critical'])->default('minor');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    // En este método se define la lógica para revertir la migración, que en este caso es eliminar la tabla 'incidents' si existe. Esto permite deshacer los cambios realizados por la migración en caso de ser necesario.
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};