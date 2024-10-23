<!-- resources/views/habitaciones/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestionar Habitaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-4">
                        <a href="{{ route('habitaciones.create') }}" class="btn btn-success">
                            {{ __('Agregar Habitación') }}
                        </a>
                    </div>

                    <table class="table table-bordered table-condensed table-striped" id="tabla-habitaciones" style="width: 100%;">
                        <thead style="background-color: #dff1ff;">
                            <tr>
                                <th style="text-align: center;">ID</th>
                                <th style="text-align: center;">Número de Habitación</th>
                                <th style="text-align: center;">Tipo</th>
                                <th style="text-align: center;">Capacidad</th>
                                <th style="text-align: center;">Detalles</th>
                                <th style="text-align: center;">Precio por Noche</th>
                                <th style="text-align: center;">Estado</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($habitaciones as $habitacion)
                                <tr>
                                    <td style="text-align: center;">{{ $habitacion->id }}</td>
                                    <td style="text-align: center;">{{ $habitacion->numero_habitacion }}</td>
                                    <td style="text-align: center;">{{ $habitacion->tipo }}</td>
                                    <td style="text-align: center;">{{ $habitacion->capacidad }}</td>
                                    <td style="text-align: center;">{{ $habitacion->detalles }}</td>
                                    <td style="text-align: center;">{{ $habitacion->precio_por_noche }}</td>
                                    <td class="status-cell px-6 py-4 whitespace-nowrap text-lg text-center
                                        {{ $habitacion->estado == 'disponible' ? 'bg-green-500 text-black' : ($habitacion->estado == 'ocupado' ? 'bg-red-500 text-white' : 'bg-gray-500 text-white') }}">
                                        {{ $habitacion->estado == 'disponible' ? 'disponible' : ($habitacion->estado == 'ocupado' ? 'ocupado' : 'reservado') }}
                                    </td>

                                    <td style="text-align: center;">
                                    <x-custom-button-habitacion :url="'admin-habitaciones/edit/'" :valor="$habitacion" >{{ __('Editar') }}</x-custom-button-habitacion>
                                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $habitacion->id }}')">{{ __('Eliminar') }}</x-danger-button>

                                        <!-- Modal para confirmación de eliminación -->
                                        <x-modal name='{{ $habitacion->id }}' :show="$errors->userDeletion->isNotEmpty()" focusable>
                                            <form method="POST" action="{{ route('habitaciones.destroy', $habitacion->id) }}" class="p-6">
                                                @csrf
                                                @method('DELETE')

                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('¿Estás seguro que deseas eliminar la habitación ') }}{{ $habitacion->numero_habitacion }}{{ __('?') }}
                                                </h2>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancelar') }}
                                                    </x-secondary-button>

                                                    <x-danger-button class="ms-3">
                                                        {{ __('Eliminar') }}
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
