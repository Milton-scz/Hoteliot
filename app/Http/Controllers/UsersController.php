<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);
        return view('Usuarios.index', compact('users'));
    }

    public function create()
    {
        $users = User::All();
        return view('Usuarios.create')->with('users', $users) ;;
    }

    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'photos' => ['required', 'array', 'min:1'], // Asegúrate de que se reciban fotos
            'photos.*' => ['string'], // Validación para cada foto como cadena Base64
        ]);

        // Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Crear la carpeta para el usuario
        $userDirectory = public_path('images/' . $user->name);
        if (!file_exists($userDirectory)) {
            if (mkdir($userDirectory, 0755, true)) {
            } else {
                return redirect()->route('admin.users')->withErrors(['msg' => 'No se pudo crear el directorio para las fotos.']);
            }
        }

        // Guardar las fotos en la carpeta
        foreach ($request->photos as $key => $photoData) {
            if ($key >= 4) break; // Salir si ya se guardaron 4 fotos

            // Convertir la cadena Base64 a un archivo
            $photo = str_replace('data:image/png;base64,', '', $photoData);
            $photo = str_replace(' ', '+', $photo);
            $photoName = ($key + 1) . '.jpg'; // Nombre de la foto como 1.jpg, 2.jpg, etc.
            $filePath = $userDirectory . '/' . $photoName;

            // Decodificar el contenido y guardar el archivo
            file_put_contents($filePath, base64_decode($photo));

        }

        return redirect()->route('admin.users')->with('status', 'User created successfully!');
    }




    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('Usuarios.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|confirmed',
            'is_admin' => 'required|boolean',
        ]);


        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->is_admin = $request->input('is_admin');
        $user->save();

        return redirect()->route('admin.users')->with('status', 'profile-updated');
    }

    public function destroy($user_id)
{
    $user = User::find($user_id);

    // Eliminar la carpeta del usuario
    $userFolder = public_path('images/' . $user->name);

    if (File::exists($userFolder)) {
        File::deleteDirectory($userFolder);
    }

    $user->delete();

    return Redirect::route('admin.users');
}
    public function auth(Request $request)
    {
        $username = $request->user;
        $user = User::where('name', $username)->first();

        if (!$user) {
            return response()->json(['message' => 'Los datos proporcionados son inválidos'], 400);
        }

        Auth::login($user);


        $request->session()->regenerate();


        return response()->json([
            'user_id' => $user->id,
            'message' => "Autenticado con éxito!",
            'redirect' => route('dashboard'),
        ], 200);
    }



}
