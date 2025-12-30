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
        Schema::create('p3_transfer_zonas', function (Blueprint $table) {
            $table->id('id_zona');

            $table->string('descripcion', 100);
            $table->string('codigo', 50);

            $table->unique('codigo', 'uk_p3_transfer_zonas_codigo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_transfer_zonas');
    }
};
