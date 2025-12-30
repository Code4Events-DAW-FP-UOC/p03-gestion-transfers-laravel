<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('p3_transfer_viajeros', function (Blueprint $table) {
            $table->id('id_viajero');

            $table->unsignedBigInteger('user_id')->nullable()->unique();
            
            $table->string('nombre', 100);
            $table->string('apellido1', 100);
            $table->string('apellido2', 100)->nullable();
            $table->string('direccion', 150);
            $table->string('codigo_postal', 20);
            $table->string('ciudad', 100);
            $table->string('pais', 100);
            $table->string('email', 150);
            $table->string('telefono', 50)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            // Indices y restrincciones
            $table->unique('email', 'uk_p3_transfer_viajeros_email');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_transfer_viajeros');
    }
};
