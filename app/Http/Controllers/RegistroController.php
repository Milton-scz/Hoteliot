<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
class RegistroController extends Controller
{
    public function index()
    {
        $registros = Registro::paginate(20);
        return view('Reservas.Registros.index', compact('registros'));
    }
}
