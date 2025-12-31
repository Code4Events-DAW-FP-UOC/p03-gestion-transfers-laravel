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
        Schema::create('p3_transfer_hoteles', function (Blueprint $table) {
            $table->id('id_hotel');

            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->unsignedBigInteger('id_zona')->nullable();

            $table->string('nombre', 150);
            $table->string('email', 150);

            $table->decimal('comision',5,2)->nullable();
            $table->string('telefono', 50)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            // Indices y restricciones
            $table->unique('email', 'uk_p3_transfer_hoteles_email');
            $table->index('id_zona', 'idx_p3_transfer_hoteles_id_zona');

            $table->foreign('user_id', 'fk_p3_transfer_hoteles_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            
            $table->foreign('id_zona', 'fk_p3_transfer_hoteles_zona')
                ->references('id_zona')
                ->on('p3_transfer_zonas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_transfer_hoteles');
    }
};
