<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;


    protected $fillable = [
        'habitacion_id',
        'cliente_id',
        'precio',
        'fecha_entrada',
        'fecha_salida',
        'estado',
    ];


    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

}
