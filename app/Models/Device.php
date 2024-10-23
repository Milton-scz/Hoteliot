<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;


    protected $fillable = [
        'uuid',
        'nombre',
        'type',
        'descripcion',
        'status',
    ];

    public function habitacion() {
        return $this->belongsTo(Habitacion::class, 'uuid', 'device_id');
    }


    /**
     * Verificar si el dispositivo está asignado a una habitación.
     *
     * @return bool
     */
    public function estaAsignado()
    {
        return $this->habitacion()->exists();
    }
}
