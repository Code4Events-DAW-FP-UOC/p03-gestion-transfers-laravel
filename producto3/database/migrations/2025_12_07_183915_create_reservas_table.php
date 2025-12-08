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
        Schema::create('p3_transfer_reservas', function (Blueprint $table) {
            $table->id('id_reserva');

            $table->string('localizador', 50);

            // Quien realiza / gestiona la reserva
            $table->unsignedBigInteger('id_hotel')->nullable();         // hotel que realiza la reserva
            $table->unsignedBigInteger('id_viajero');                   // viajero a nombre de quien va la reserva
            $table->unsignedBigInteger('id_creador');                   // usuario (cliente/admin) que creó la reserva
            $table->unsignedBigInteger('id_modificador')->nullable();   // último usuario que modifico la reserva
            $table->unsignedBigInteger('id_tipo_reserva');

            $table->unsignedBigInteger('id_precio');

            // Fechas de gestión
            $table->dateTime('fecha_reserva');
            $table->dateTime('fecha_modificacion')->nullable();

            // Datos del servicio / destino
            $table->unsignedBigInteger('id_hotel_destino');             // hotel donde se aloja el viajero
            $table->integer('num_viajeros')->default(1);
            $table->unsignedBigInteger('id_vehiculo');

            // Vuelo de entrada (llegada)
            $table->date('fecha_entrada')->nullable();
            $table->time('hora_entrada')->nullable();
            $table->string('numero_vuelo_entrada', 50)->nullable();
            $table->string('origen_vuelo_entrada', 100)->nullable();

            // Vuelo de salida (regreso)
            $table->date('fecha_vuelo_salida')->nullable();
            $table->time('hora_vuelo_salida')->nullable();
            $table->string('numero_vuelo_salida', 50)->nullable();
            $table->string('destino_vuelo_salida', 100)->nullable();

            // Control y estado
            $table->enum('estado', ['pendiente','confirmada','cancelada','realizada'])->default('pendiente');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // Índices
            $table->unique('localizador', 'uk_p3_transfer_reservas_localizador');
            $table->index('id_hotel', 'idx_p3_transfer_reservas_id_hotel');
            $table->index('id_viajero', 'idx_p3_transfer_reservas_id_viajero');
            $table->index('id_creador', 'idx_p3_transfer_reservas_id_creador');
            $table->index('id_modificador', 'idx_p3_transfer_reservas_id_modificador');
            $table->index('id_tipo_reserva', 'idx_p3_transfer_reservas_id_tipo_reserva');
            $table->index('id_hotel_destino', 'idx_p3_transfer_reservas_id_hotel_destino');
            $table->index('id_vehiculo', 'idx_p3_transfer_reservas_id_vehiculo');

            /* RELACIONES y RESTRICCIONES */

            // NO se puede borrar un hotel si tiene reservas
            $table->foreign('id_hotel', 'fk_p3_transfer_reservas_hotel')
                ->references('id_hotel')
                ->on('p3_transfer_hoteles')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            // NO se puede borrar un viajero si tiene reservas
            $table->foreign('id_viajero', 'fk_p3_transfer_reservas_viajero')
                ->references('id_viajero')
                ->on('p3_transfer_viajeros')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            
            // Creador -> tabla users
            $table->foreign('id_creador', 'fk_p3_transfer_reservas_creador')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            
            // Modificador -> tabla users
            $table->foreign('id_modificador', 'fk_p3_transfer_reservas_modificador')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            // Tipo de reserva
            $table->foreign('id_tipo_reserva', 'fk_p3_transfer_reservas_tipo_reserva')
                ->references('id_tipo_reserva')
                ->on('p3_transfer_tipos_reservas')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            // Zona
            $table->foreign('id_hotel_destino', 'fk_p3_transfer_reservas_hotel_destino')
                ->references('id_hotel')
                ->on('p3_transfer_hoteles')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            
            // Vehículo
            $table->foreign('id_vehiculo', 'fk_p3_transfer_reservas_vehiculo')
                ->references('id_vehiculo')
                ->on('p3_transfer_vehiculos')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            // Precio
            $table->foreign('id_precio', 'fk_p3_transfer_reservas_precio')
                ->references('id_precio')
                ->on('p3_transfer_precios')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_transfer_reservas');
    }
};
