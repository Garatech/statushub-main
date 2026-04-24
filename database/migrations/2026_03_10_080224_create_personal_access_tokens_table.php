<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Esta migración se creó para almacenar los tokens de acceso personal de los usuarios, lo que permite a los usuarios generar tokens para autenticarse en la API sin necesidad de compartir sus credenciales. Cada token puede tener diferentes permisos y una fecha de expiración, lo que mejora la seguridad y el control sobre el acceso a la API.
    public function up(): void
    {
        /**
         * La tabla 'personal_access_tokens' se utiliza para almacenar los tokens de acceso personal generados por los usuarios. Cada token está asociado a un modelo (como un usuario) a través de una relación polimórfica, lo que permite que diferentes tipos de modelos puedan tener tokens. La tabla incluye campos para el nombre del token, el valor del token (que es único), las habilidades o permisos asociados al token, la última vez que se usó el token y la fecha de expiración del token. Esto proporciona una forma segura y flexible de gestionar el acceso a la API mediante tokens personalizados.
         * - `id`: Identificador único del token.
         * - `tokenable_type` y `tokenable_id`: Campos para la relación polimórfica que asocia el token a un modelo específico (como un usuario).
         * - `name`: Nombre descriptivo del token.
         * - `token`: El valor del token, que es único.
         * - `abilities`: Un campo de texto que puede almacenar las habilidades o permisos asociados al token.
         * - `last_used_at`: Marca de tiempo que indica la última vez que se usó el token.
         * - `expires_at`: Marca de tiempo que indica cuándo expira el token, con un índice para mejorar las consultas basadas en la expiración.
         * - `timestamps`: Campos de marca de tiempo para registrar cuándo se creó y actualizó el token.
         */
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
