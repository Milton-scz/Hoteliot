<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Habitacion;
use App\Models\Cliente;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Habitacion::class);
            $table->foreignIdFor(Cliente::class);
            $table->float('precio');
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
