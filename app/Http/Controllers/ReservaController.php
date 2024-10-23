<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Habitacion;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ReservaController extends Controller
{

    public function index()
    {
        $reservas = Reserva::with('habitacion', 'cliente')->get();
        return view('reservas.index', compact('reservas'));
    }


    public function create()
    {
        $habitaciones = Habitacion::where('estado', 'disponible')->get();
        $clientes = Cliente::all();
        return view('reservas.create', compact('habitaciones', 'clientes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'habitacion_id' => 'required|exists:habitacions,id',
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'required|date|after:fecha_entrada',
            'estado' => 'required|in:pendiente,confirmada,cancelada'
        ]);

        Reserva::create($request->all());

        return redirect()->route('reservas.index');
    }


    public function edit(Reserva $reserva)
    {
        $habitaciones = Habitacion::where('estado', 'disponible')->get();
        $clientes = Cliente::all();
        return view('reservas.edit', compact('reserva', 'habitaciones', 'clientes'));
    }


    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'habitacion_id' => 'required|exists:habitacions,id',
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'required|date|after:fecha_entrada',
            'estado' => 'required|in:pendiente,confirmada,cancelada'
        ]);

        $reserva->update($request->all());

        return redirect()->route('reservas.index');
    }


    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('reservas.index');
    }
}
