<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class DeviceController extends Controller
{
  public function index(Request $request)
{
    $search = $request->input('search');

    if ($search) {
        $devices = Device::with('habitacion')
            ->whereHas('habitacion', function ($query) use ($search) {
                $query->where('numero_habitacion', 'LIKE', "%{$search}%");
            })
            ->paginate(10);
    } else {
        $devices = Device::with('habitacion')->paginate(10);
    }


    if ($request->ajax()) {
        return view('Devices.partials.devices_table', compact('devices'))->render();
    }

    return view('Devices.index', compact('devices'));
}


    public function create()
    {
        $habitaciones = Habitacion::all();

        return view('Devices.create', compact('habitaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'uuid' => 'required|unique:devices|max:255',
            'nombre' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',

        ]);

          Device::create([
            'uuid' => $request->uuid,
            'nombre' => $request->nombre,
            'type' => $request->type,
            'descripcion' => $request->descripcion,
            'status' => $request->status,
        ]);

        return redirect()->route('devices')->with('success', 'Dispositivo creado exitosamente.');
    }

    public function edit($id) {

        $device = Device::where('id', $id)->firstOrFail();
        return view('Devices.edit')->with("device", $device);
    }


      public function update(Request $request, $uuid) {
        $device = Device::where('uuid', $uuid)->firstOrFail();

        $device->fill($request->all());

        $device->save();
        return redirect()->route('devices')->with('success', 'Dispositivo actualizado con éxito.');
    }


    public function destroy($uuid) {
        $device = Device::where('uuid', $uuid)->firstOrFail();
        $device->delete();
        return redirect()->route('devices')->with('success', 'Dispositivo eliminado con éxito.');
    }

}
