<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ApiDeviceController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\RegistroController;


Route::get('/api/device/update/{uuid}', [ApiDeviceController::class, 'update']);


Route::get('/api/device/search/{uuid}', [ApiDeviceController::class, 'search']);

Route::get('/buscar-habitaciones', [HabitacionController::class, 'buscarHabitaciones'])->name('buscar.habitaciones');

Route::post('/login-ia/auth', [UsersController::class, 'auth'])->name('login.ia.auth');



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // GESTIONAR USUARIOS
    Route::get('/admin-users', [UsersController::class, 'index'])->name('admin.users');
    Route::get('/admin-users/create', [UsersController::class, 'create'])->name('admin.users.create');
    Route::post('/admin-users/register', [UsersController::class, 'store'])->name('admin.users.register');
    Route::get('/admin-users/edit/{user_id}', [UsersController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin-users/edit/{user_id}', [UsersController::class, 'update'])->name('admin.users.update');
    Route::delete('admin-users/destroy/{user_id}', [UsersController::class, 'destroy'])->name('admin.users.delete');

     // GESTIONAR CLIENTES
     Route::get('/admin-clientes', [ClienteController::class, 'index'])->name('clientes');
     Route::get('/admin-clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
     Route::post('/admin-clientes/register', [ClienteController::class, 'store'])->name('clientes.store');
     Route::get('/admin-clientes/edit/{cliente_id}', [ClienteController::class, 'edit'])->name('clientes.edit');
     Route::patch('/admin-clientes/edit/{cliente_id}', [ClienteController::class, 'update'])->name('clientes.update');
     Route::delete('admin-clientes/destroy/{cliente_id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

     // GESTIONAR HABITACIONES
     Route::get('/admin-habitaciones', [HabitacionController::class, 'index'])->name('habitaciones');
     Route::get('/admin-habitaciones/create', [HabitacionController::class, 'create'])->name('habitaciones.create');
     Route::post('/admin-habitaciones/register', [HabitacionController::class, 'store'])->name('habitaciones.store');
     Route::get('/admin-habitaciones/edit/{habitacion_id}', [HabitacionController::class, 'edit'])->name('habitaciones.edit');
     Route::patch('/admin-habitaciones/edit/{habitacion_id}', [HabitacionController::class, 'update'])->name('habitaciones.update');
     Route::delete('admin-habitaciones/destroy/{habitacion_id}', [HabitacionController::class, 'destroy'])->name('habitaciones.destroy');

      // GESTIONAR DEVICES
      Route::get('/admin-devices', [DeviceController::class, 'index'])->name('devices');
      Route::get('/admin-devices/create', [DeviceController::class, 'create'])->name('devices.create');
      Route::post('/admin-devices/register', [DeviceController::class, 'store'])->name('devices.store');
      Route::get('/admin-devices/edit/{uuid}', [DeviceController::class, 'edit'])->name('devices.edit');
      Route::patch('/admin-devices/edit/{uuid}', [DeviceController::class, 'update'])->name('devices.update');
      Route::delete('admin-devices/destroy/{uuid}', [DeviceController::class, 'destroy'])->name('devices.destroy');


        // GESTIONAR RECEPCION
        Route::get('/admin-recepciones', [RecepcionController::class, 'index'])->name('recepciones');
        Route::get('/admin-recepciones/create/{habitacion_id}', [RecepcionController::class, 'create'])->name('recepciones.create');
        Route::post('/admin-recepciones/register', [RecepcionController::class, 'store'])->name('recepciones.store');
        Route::get('/admin-recepciones/edit/{habitacion_id}', [RecepcionController::class, 'edit'])->name('recepciones.edit');
        Route::patch('/admin-recepciones/edit', [RecepcionController::class, 'update'])->name('recepciones.update');
        Route::delete('admin-recepciones/destroy/{recepcion_id}', [RecepcionController::class, 'destroy'])->name('recepciones.destroy');

        Route::get('/admin-recepciones/details', [RecepcionController::class, 'details'])->name('recepciones.details');


        // GESTIONAR REGISTROS
        Route::get('/admin-registros', [RegistroController::class, 'index'])->name('registros');
});

require __DIR__.'/auth.php';
