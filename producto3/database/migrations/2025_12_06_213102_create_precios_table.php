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
        Schema::create('p3_transfer_precios', function (Blueprint $table) {
            $table->id('id_precio');

            $table->unsignedBigInteger('id_vehiculo');
            $table->unsignedBigInteger('id_hotel');
            $table->decimal('precio',10,2);

            $table->unique(['id_hotel', 'id_vehiculo'], 'uk_p3_transfer_precios_hotel_vehiculo');

            $table->foreign('id_vehiculo', 'fk_p3_transfer_precios_vehiculo')
                ->references('id_vehiculo')
                ->on('p3_transfer_vehiculos')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign('id_hotel', 'fk_p3_transfer_precios_hotel')
                ->references('id_hotel')
                ->on('p3_transfer_hoteles')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_transfer_precios');
    }
};
