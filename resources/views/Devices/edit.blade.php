<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Actualizar Dispositivo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('devices.update', $device->uuid) }}">
                        @csrf
                        @method('PATCH')  <!-- Método PUT para actualización -->

                        <div class="mb-4">
                            <label for="uuid" class="block text-sm font-medium text-gray-700">UUID</label>
                            <input type="text" name="uuid" id="uuid" value="{{ $device->uuid }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required readonly>
                        </div>

                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="{{ $device->nombre }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                            <input type="text" name="type" id="type" value="{{ $device->type }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input type="text" name="descripcion" id="descripcion" value="{{ $device->descripcion }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Estatus</label>
                            <select name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                <option value="1" {{ $device->status ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !$device->status ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="mt-6">
                            <x-primary-button>
                                {{ __('Actualizar Dispositivo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
