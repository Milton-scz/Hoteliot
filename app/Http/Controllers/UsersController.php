<?php

namespace App\Http\Controllers;


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
        return view('Usuarios.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

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
