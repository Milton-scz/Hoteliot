<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_habitacion',
        'tipo',
        'capacidad',
        'detalles',
        'precio_por_noche',
        'estado',
        'device_id',
    ];


    public function devices() {
        return $this->hasMany(Device::class);
    }
    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function recepciones() {
        return $this->hasMany(Recepcion::class);
    }

}

