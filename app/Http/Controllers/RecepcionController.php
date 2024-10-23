<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use Illuminate\Http\Request;
use App\Models\Habitacion;
use App\Models\Cliente;
class RecepcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $habitaciones = Habitacion::all();
        return view('Reservas.Recepcion.index', compact('habitaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( $habitacion_id)
    {
        $habitacion = Habitacion::findOrFail($habitacion_id);
        $clientes = Cliente::all();
        return view('Reservas.Recepcion.create', compact('habitacion', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'habitacion_id' => 'required|exists:habitacions,id',
            'cliente_id' => 'required|exists:clientes,id',
            'tipo_registro' => 'required|string',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'required|date|after:fecha_entrada',
            'total_a_pagar' => 'required|numeric',
            'descuento' => 'nullable|numeric',
            'adelanto' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
        ]);


        $recepcion = Recepcion::create($request->all());
        $habitacion = Habitacion::findOrFail($request->habitacion_id);
        if ($request->tipo_registro === "reservar") {
            $habitacion->estado = 'reservado';
        } else {
            $habitacion->estado = 'ocupado';

        }
        $habitacion->save();

        $habitaciones = Habitacion::all();

        return redirect()->route('recepciones')->with('habitaciones', $habitaciones );

    }

    /**
     * Display the specified resource.
     */
    public function show(Recepcion $recepcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($habitacion_id) {

        $habitacion = Habitacion::with('recepciones.cliente')->findOrFail($habitacion_id);


      $recepcion = $habitacion->recepciones()->latest()->first();


        $cliente = $recepcion ? $recepcion->cliente : null;


        return view('Reservas.Recepcion.edit', [
            'habitacion' => $habitacion,
            'recepcion' => $recepcion,
            'cliente' => $cliente,
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'habitacion_id' => 'required|exists:habitacions,id',
            'recepcion_id' => 'required|exists:recepcions,id',
            'total_a_pagar' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'adelanto' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
        ]);

        $habitacion = Habitacion::findOrFail($request->habitacion_id);
        if($habitacion->estado=="reservado"){
            $habitacion->estado = 'ocupado'; // Asegúrate de que 'estado' sea el campo correcto
            $habitacion->save();
        }else{
            $recepcion = Recepcion::findOrFail($request->recepcion_id);
            $recepcion->total_a_pagar = $request->total_a_pagar;
            $recepcion->descuento = $request->descuento;
            $recepcion->adelanto = $request->adelanto;
            $recepcion->observaciones = $request->observaciones;
            $recepcion->save();
            $habitacion->estado = 'disponible'; // Asegúrate de que 'estado' sea el campo correcto
            $habitacion->save();
        }

        return redirect()->route('recepciones')->with('success', 'Recepción actualizada y habitación marcada como disponible.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recepcion $recepcion)
    {
        //
    }
}
