<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_codes', function (Blueprint $table) {
            $table->id(); //Identificador unico de la tabla
            $table->unsignedBigInteger('user_id'); // Relación con la tabla users
            $table->string('code'); // Código de 6 dígitos
            $table->boolean('active'); // Campo para saber si el codigo aun esta activo
            $table->timestamp('expires_at'); // Fecha de expiración del código
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_codes');
    }
};
