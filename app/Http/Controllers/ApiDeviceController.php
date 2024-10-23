<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;

class ApiDeviceController extends Controller
{
    public function update($uuid)
    {
        // Buscar el dispositivo por UUID
        $device = Device::where('uuid', $uuid)->first();

        if ($device) {
            // Cambiar el estado: si es true (activado), lo cambia a false, y viceversa
            $device->status = !$device->status;

            // Guardar los cambios en la base de datos
            $device->save();

            // Devolver una respuesta con el nuevo estado del dispositivo
            return response()->json([
                'success' => true,
                'message' => 'Status cambiado correctamente',
                'new_status' => $device->status,
                'Device' => $device
            ], 200);
        } else {
            // Dispositivo no encontrado
            return response()->json([
                'success' => false,
                'message' => 'Dispositivo no encontrado'
            ], 404);
        }
    }

    public function search($uuid)
    {
        $device = Device::where('uuid', $uuid)->first();
        if ($device) {
            return response()->json(['success' => true, 'Device' => $device], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Guía no encontrada'], 404);
        }
    }
}
