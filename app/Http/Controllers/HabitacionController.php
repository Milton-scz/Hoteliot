<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class HabitacionController extends Controller
{
    public function index()
    {
        $habitaciones = Habitacion::all();
        return view('Habitaciones.index', compact('habitaciones'));
    }

    public function create()
    {

       $devicesDisponibles = Device::whereNotIn('uuid', function($query) {
        $query->select('device_id')->from('habitacions');
    })->get();

    return view('Habitaciones.create', compact('devicesDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_habitacion' => 'required|unique:habitacions',
            'tipo' => 'required',
            'capacidad' => 'required|integer',
            'precio_por_noche' => 'required|numeric',
            'estado' => 'required',
            'device_id' => 'required'
        ]);

        Habitacion::create($request->all());

        return redirect()->route('habitaciones');
    }

    public function edit($habitacion_id){
        $habitacion = Habitacion::findOrFail($habitacion_id);

      $devicesDisponibles = Device::whereNotIn('uuid', function($query) {
        $query->select('device_id')->from('habitacions');
    })->get();

     if ($devicesDisponibles->isEmpty() && isset($habitacion)) {
        $devicesDisponibles = Device::where('uuid', $habitacion->device_id)->get();
    }

        return view('Habitaciones.edit')->with("habitacion", $habitacion)->with("devicesDisponibles", $devicesDisponibles);
    }


      public function update(Request $request, $id){
        $habitacion = Habitacion::findOrFail($id);
        $habitacion->fill($request->all());
        $habitacion->save();
        return Redirect::route('habitaciones');
    }


    public function destroy($habitacion_id)
    {
     $habitacion = Habitacion::find($habitacion_id);

        $habitacion->delete();
        return redirect()->route('habitaciones')->with('success', 'Habitacion eliminada exitosamente.');
    }


    public function buscarHabitaciones(Request $request)
    {

        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');


        $habitacionesDisponibles = Habitacion::where('estado', 'disponible')
            ->whereDoesntHave('recepciones', function ($query) use ($fechaInicio, $fechaFin) {
                $query->where(function ($query) use ($fechaInicio, $fechaFin) {
                    $query->whereBetween('fecha_entrada', [$fechaInicio, $fechaFin])
                          ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
                          ->orWhere(function ($query) use ($fechaInicio, $fechaFin) {
                              $query->where('fecha_salida', '<=', $fechaInicio)
                                    ->where('fecha_entrada', '>=', $fechaFin);
                          });
                });
            })
            ->get();


        return view('welcome', compact('habitacionesDisponibles', 'fechaInicio', 'fechaFin'));
    }



}
