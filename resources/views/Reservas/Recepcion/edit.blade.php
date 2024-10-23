<!-- Incluir CSS de Bootstrap -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Incluir JS de Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Estado del Hospedaje') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Distribución en 4 columnas para detalles de la habitación y cliente -->
                    <div class="row border p-4 rounded-lg bg-gray-50">
                        <div class="col-md-3 mb-3">
                            <p class="text-lg font-bold">N° HABITACIÓN:</p>
                            <p class="text-sm ">{{ $habitacion->numero_habitacion }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <p class="text-lg font-bold">CELULAR:</p>
                            <p class="text-sm ">{{ $cliente->telefono }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <p class="text-lg font-bold">TIPO DE HABITACIÓN:</p>
                            <p class="text-sm">{{ $habitacion->tipo }}</p>
                        </div>

                        <div class="col-md-3 mb-3">
                            <p class="text-lg font-bold">PRECIO POR NOCHE:</p>
                            <p class="text-sm">{{ $habitacion->precio_por_noche }} Bs.</p>
                        </div>

                        <div class="col-md-3 mb-3">
                            <p class="text-lg  font-bold">NOMBRE DEL CLIENTE:</p>
                            <p class="text-sm  ">{{ $cliente->nombre }}</p>
                        </div>

                        <div class="col-md-3 mb-3">
                            <p class="text-lg font-bold">INGRESO:</p>
                            <p class="text-sm ">{{ $recepcion->fecha_entrada }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <p class="text-lg font-bold">SALIDA:</p>
                            <p class="text-sm ">{{ $recepcion->fecha_salida }}</p>
                        </div>
                    </div>

                    <!-- Sección para Detalles del Alojamiento -->
                    <form method="POST" action="{{ route('recepciones.update') }}" class="mt-6">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="habitacion_id" value="{{ $habitacion->id }}">
                        <input type="hidden" name="recepcion_id" value="{{ $recepcion->id }}">

                        <h2 class="text-lg mb-4">Detalles del Alojamiento</h2>

                        <!-- Distribución en 4 columnas para los detalles de pago -->
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label for="total_a_pagar" class="block text-sm font-medium text-gray-700">Total a pagar</label>
                                <input type="number" step="0.01" name="total_a_pagar" id="total_a_pagar" class="form-control" value="{{$recepcion->total_a_pagar}}" required>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label for="descuento" class="block text-sm font-medium text-gray-700">Descuento</label>
                                <input type="number" step="0.01" name="descuento" id="descuento" class="form-control" value="{{$recepcion->descuento}}" required>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label for="adelanto" class="block text-sm font-medium text-gray-700">Adelanto</label>
                                <input type="number" step="0.01" name="adelanto" id="adelanto" class="form-control" value="{{$recepcion->adelanto}}" required>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="form-control" placeholder="Escribir algún detalle que tenga el registro">{{ $recepcion->observaciones }}</textarea>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-6 flex justify-center">
                             <a href="{{ route('recepciones') }}" class="bg-danger hover:bg-danger-dark text-white font-bold py-2 px-4 rounded-lg">
                                        {{ __('Volver Atrás') }}
                                    </a>
                                    @if($habitacion->estado === 'reservado')
                                        <button class="btn btn-success mr-4">
                                            {{ __('Empezar') }}
                                        </button>
                                    @else
                                        <x-primary-button class="mr-4">
                                            {{ __('Culminar Hospedaje') }}
                                        </x-primary-button>
                                    @endif

                                </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
