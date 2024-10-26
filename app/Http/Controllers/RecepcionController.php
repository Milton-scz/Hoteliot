<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use Illuminate\Http\Request;
use App\Models\Habitacion;
use App\Models\Cliente;
use App\Models\Registro;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;
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


    public function details()
    {
        // Cargar las recepciones con sus clientes y habitaciones
        $recepciones = Recepcion::with(['cliente', 'habitacion'])->paginate(10);

        return view('Reservas.Recepcion.details', compact('recepciones'));
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

 // Realiza la interacción con tu contrato inteligente para registrar la renta
 $response = Http::post('http://45.79.209.76:3000/api/rent-room', [
    'cedulaCliente' => $request->cliente_id,
    'numberRoom' =>  $request->habitacion_id,
])->throw();

$responseData = $response->json();

if (isset($responseData['trxhash'])) {
    $requestData = $request->all();
    $requestData['trxhash'] = $responseData['trxhash'];
    $recepcion = Recepcion::create($requestData);
    $registroData = [
        'trxhash' => $responseData['trxhash'],
    ];
    $registro = Registro::create($registroData);
} else {
    // Manejo de error si trxhash no está presente
    return response()->json(['error' => 'Transaction hash not found.'], 400);
}

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

        $response = Http::post('http://45.79.209.76:3000/api/change-status', [
            'numberRoom' =>  $recepcion->habitacion->numero_habitacion,
        ])->throw();


        $responseData = $response->json();

        if (isset($responseData['trxhash'])) {
            $requestData['trxhash'] = $responseData['trxhash'];
            $registro = Registro::create($requestData);
        } else {
            return response()->json(['error' => 'Transaction hash not found.'], 400);
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
