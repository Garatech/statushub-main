<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Esta migration crea la tabla 'incident_updates' para almacenar las actualizaciones de los incidentes, incluyendo el mensaje, el estado y las referencias al incidente y al usuario que hizo la actualización.
return new class extends Migration
{
    // Aquí se define la estructura de la tabla 'incident_updates' con sus columnas y relaciones.
    public function up(): void
    {
        // Crea la tabla 'incident_updates' con las siguientes columnas:
        // - id: clave primaria auto-incremental.
        // - incident_id: clave foránea que referencia a la tabla 'incidents' y se elimina en cascada.
        // - user_id: clave foránea que referencia a la tabla 'users' y se elimina en cascada.
        // - message: texto que contiene el mensaje de la actualización.
        // - status: enum que indica el estado del incidente en esta actualización (investigating, identified, monitoring, resolved).
        Schema::create('incident_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->enum('status', ['investigating', 'identified', 'monitoring', 'resolved']);
            $table->timestamps();
        });
    }

    // En el método down se define la acción para revertir la migración, que en este caso es eliminar la tabla 'incident_updates'.
    public function down(): void
    {
        Schema::dropIfExists('incident_updates');
    }
};