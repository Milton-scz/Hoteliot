<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class ClienteController extends Controller
{
   // Mostrar la lista de clientes
   public function index()
   {
       $clientes = Cliente::all();
       return view('Clientes.index', compact('clientes'));
   }


   public function create()
   {
       return view('Clientes.create');
   }


   public function store(Request $request)
   {
       $request->validate([
           'nombre' => 'required|string|max:255',
           'cedula' => 'required|string|max:20|unique:clientes,cedula',
           'correo_electronico' => 'required|email|unique:clientes,correo_electronico|max:255',
           'telefono' => 'required|string|max:15',
           'direccion' => 'nullable|string|max:255',
       ]);

       Cliente::create($request->all());

       return redirect()->route('clientes')->with('success', 'Cliente creado exitosamente.');
   }



   public function edit($cliente_id){
    $cliente = Cliente::findOrFail($cliente_id);

    return view('Clientes.edit')->with("cliente", $cliente);
}


   public function update(Request $request, $id){
    $cliente = Cliente::findOrFail($id);
    $cliente->fill($request->all());
    $cliente->save();
    return Redirect::route('clientes');
}


   public function destroy($cliente_id)
   {
    $cliente = Cliente::find($cliente_id);

       $cliente->delete();
       return redirect()->route('clientes')->with('success', 'Cliente eliminado exitosamente.');
   }
}
