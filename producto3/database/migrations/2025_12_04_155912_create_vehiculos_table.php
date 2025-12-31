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
        Schema::create('p3_transfer_vehiculos', function (Blueprint $table) {
            $table->id('id_vehiculo');

            $table->string('descripcion', 100);
            $table->string('email', 150);
            $table->string('matricula', 20);
            $table->unsignedInteger('plazas');
            $table->boolean('activo')->default(true);

            $table->unique('email', 'uk_p3_transfer_vehiculos_email');
            $table->unique('matricula', 'uk_p3_transfer_vehiculos_matricula');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_transfer_vehiculos');
    }
};
