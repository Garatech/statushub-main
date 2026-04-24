<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Esta migración se creó para almacenar las suscripciones de los usuarios a los servicios, lo que permite a los usuarios recibir notificaciones sobre el estado de los servicios a los que están suscritos. Cada suscripción está asociada a un servicio específico y a una dirección de correo electrónico, y se utiliza un token único para gestionar la suscripción. Además, se incluye un campo para indicar si la suscripción está activa o no, lo que facilita la gestión de las suscripciones y el envío de notificaciones solo a los usuarios que han optado por recibirlas.
return new class extends Migration
{
    public function up(): void
    {
        /** 
         *  La tabla 'subscriptions' se utiliza para almacenar las suscripciones de los usuarios a los servicios. Cada suscripción está asociada a un servicio específico a través de una clave foránea, y se identifica por la dirección de correo electrónico del usuario y un token único que se utiliza para gestionar la suscripción. La tabla también incluye un campo booleano para indicar si la suscripción está activa o no, lo que permite a los usuarios cancelar su suscripción si lo desean. Además, se establece una restricción única en la combinación de 'service_id' y 'email' para evitar que un mismo usuario se suscriba varias veces al mismo servicio.
         * - `id`: Identificador único de la suscripción.
         * - `service_id`: Clave foránea que referencia al servicio al que el usuario se ha suscrito, con una restricción de eliminación en cascada para eliminar las suscripciones si el servicio es eliminado.
         * - `email`: Dirección de correo electrónico del usuario que se ha suscrito.
         * - `token`: Un token único que se utiliza para gestionar la suscripción, como cancelar la suscripción o verificar su estado.
         * - `active`: Un campo booleano que indica si la suscripción está activa o no, lo que permite a los usuarios cancelar su suscripción si lo desean.
         * - `timestamps`: Campos de marca de tiempo para registrar cuándo se creó y actualizó la suscripción.
         */
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('token')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['service_id', 'email']);
        });
    }

    // La función 'down' se encarga de revertir la migración, eliminando la tabla 'subscriptions' si es necesario. Esto es útil para deshacer cambios en la base de datos si se produce algún error o si se desea volver a un estado anterior.
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};