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
    public function up(): void
    {
        Schema::create('recepcions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Habitacion::class);
            $table->foreignIdFor(Cliente::class);
            $table->date('fecha_entrada');  // Fecha de entrada
            $table->date('fecha_salida');  // Fecha de salida
            $table->decimal('adelanto', 8, 2)->nullable();  // Adelanto en el pago
            $table->decimal('descuento', 8, 2)->nullable();  // Descuento aplicado
            $table->decimal('total_a_pagar', 8, 2);  // Total calculado a pagar
            $table->text('observaciones')->nullable();  // Observaciones opcionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcions');
    }
};
