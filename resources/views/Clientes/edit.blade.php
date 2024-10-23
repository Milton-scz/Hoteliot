<!-- resources/views/clientes/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('clientes.update', $cliente->id) }}">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required class="mt-1 block w-full" />
                            @error('nombre')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="cedula" class="block font-medium text-sm text-gray-700">Cédula</label>
                            <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $cliente->cedula) }}" required class="mt-1 block w-full" />
                            @error('cedula')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="correo_electronico" class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
                            <input type="email" id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico', $cliente->correo_electronico) }}" required class="mt-1 block w-full" />
                            @error('correo_electronico')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block font-medium text-sm text-gray-700">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required class="mt-1 block w-full" />
                            @error('telefono')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="direccion" class="block font-medium text-sm text-gray-700">Dirección</label>
                            <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $cliente->direccion) }}" class="mt-1 block w-full" />
                            @error('direccion')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Actualizar Cliente') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
