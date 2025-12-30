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
        Schema::create('p3_transfer_tipos_reservas', function (Blueprint $table) {
            $table->id('id_tipo_reserva');

            $table->string('descripcion');
            $table->string('codigo');
            
            $table->timestamps();

            $table->unique('codigo', 'uk_p3_transfer_tipos_reservas_codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_transfer_tipos_reservas');
    }
};
