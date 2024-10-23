<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Device;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habitacions', function (Blueprint $table) {
            $table->id();
            $table->string('numero_habitacion');
            $table->enum('tipo', ['simple', 'doble', 'matrimonial']);
            $table->integer('capacidad');
            $table->string('detalles');
            $table->float('precio_por_noche');
            $table->enum('estado', ['ocupado', 'disponible', 'reservado','limpieza']);
            // Agregar la columna device_id con la relación opcional (nullable)
            $table->foreignIdFor(Device::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitacions');
    }
};
