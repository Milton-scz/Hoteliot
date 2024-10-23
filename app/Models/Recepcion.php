<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'habitacion_id',
        'cliente_id',
        'fecha_entrada',
        'fecha_salida',
        'adelanto',
        'descuento',
        'total_a_pagar',
        'observaciones',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function habitacion() {
        return $this->belongsTo(Habitacion::class);
    }


}
