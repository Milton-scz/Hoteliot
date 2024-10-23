<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('GESTIONAR CLIENTES') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <section id="contenido_principal">
                        <div class="col-md-12" style="margin-top: 10px;">
                            <div class="box box-default" style="border: 1px solid #574B90; min-height: 35px;">
                                <a href="{{ route('clientes.create') }}" class="btn btn-success" style="font-size: 13px; margin-top: 5px; margin-left: 5px;"> Agregar Cliente </a>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="box box-default" style="border: 1px solid #0c0c0c;">
                                <div class="max-w-8xl mx-auto sm:px-6 lg:px-8" style="padding: 5px;">
                                    <div style="height: 100%; overflow: auto;">
                                        <table class="table table-bordered table-condensed table-striped" id="tabla-clientes" style="width: 100%;">
                                            <!-- Encabezados de la tabla -->
                                            <thead>
                                                <th colspan="2"></th>
                                            </thead>
                                            <thead style="background-color: #dff1ff;">
                                                <th style="text-align: center;">ID</th>
                                                <th style="text-align: center;">Nombre</th>
                                                <th style="text-align: center;">Cedula</th>
                                                <th style="text-align: center;">Correo</th>
                                                <th style="text-align: center;">Teléfono</th>
                                                <th style="text-align: center;">Acción</th>
                                            </thead>
                                            <tbody>
                                                @foreach($clientes as $cliente)
                                                    <tr>
                                                        <td style="text-align: center;">{{ $cliente->id }}</td>
                                                        <td style="text-align: center;">{{ $cliente->nombre }}</td>
                                                        <td style="text-align: center;">{{ $cliente->cedula }}</td>
                                                        <td style="text-align: center;">{{ $cliente->correo_electronico }}</td>
                                                        <td style="text-align: center;">{{ $cliente->telefono }}</td>
                                                        <td style="text-align: center;">
                                                        <x-custom-button :url="'admin-clientes/edit/'" :valor="$cliente" >{{ __('Editar') }}</x-custom-button>
                                                            <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal','{{$cliente->id}}')">{{ __('Eliminar') }}</x-danger-button>

                                                            <x-modal name='{{$cliente->id}}' :show="$errors->userDeletion->isNotEmpty()" focusable>
                                                                <form method="POST" action="{{ route('clientes.destroy', ['cliente_id' => $cliente->id]) }}" class="p-6">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                                        {{ __('¿Estás seguro que deseas eliminar el cliente ') }}{{ $cliente->nombre }}{{ __('?') }}
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
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
