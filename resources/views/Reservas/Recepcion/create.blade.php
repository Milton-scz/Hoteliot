<!-- Incluir CSS de Bootstrap -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Incluir JS de Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<style>
    .custom-bg-black {
        background-color: black;
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="custom-bg-black text-white font-bold py-2 px-4 rounded-lg">DETALLES DE HABITACIÓN</h2>


                    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">


                <div class="row">
                    <div class="col-md-4">
                        <p class="text-sm font-bold">N° HABITACIÓN:</p>
                        <p class="text-sm font-medium">{{ $habitacion->numero_habitacion }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-sm font-bold">TIPO DE HABITACIÓN:</p>
                        <p class="text-sm font-medium">{{ $habitacion->tipo }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-sm font-bold">DESCRIPCIÓN:</p>
                        <p class="text-sm font-medium">{{ $habitacion->detalles }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-sm font-bold">ESTADO:</p>
                        <p class="bg-success  text-white font-bold py-2 px-4 rounded-lg">{{ $habitacion->estado }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-sm font-bold">PRECIO:</p>
                        <p class="text-sm font-medium">{{ $habitacion->precio_por_noche }}</p>
                    </div>
                </div>


                <form method="POST" action="{{ route('recepciones.store') }}" class="mt-6">
                    @csrf
                    <input type="hidden" name="habitacion_id" value="{{ $habitacion->id }}">

                </form>
            </div>
        </div>
    </div>
</div>



                    <form method="POST" action="{{ route('recepciones.store') }}" class="mt-6">
                        @csrf

                        <input type="hidden" name="habitacion_id" value="{{ $habitacion->id }}">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="cliente" class="text-sm font-bold">CLIENTE</label>
                                <div class="flex items-center">
                                    <select name="cliente_id" id="cliente_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1">
                                        <option value="">-- Seleccionar cliente --</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ $loop->last ? 'selected' : '' }}>
                                                {{ $cliente->cedula }} - {{ $cliente->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-primary-button  type="button" class="ml inline-flex items-center  border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-toggle="modal" data-target="#addClienteModal">
                                        +Cliente
                                    </x-primary-button >
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="tipo_registro" class="text-sm font-bold">TIPO DE REGISTRO</label>
                                <select name="tipo_registro" id="tipo_registro" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" required>
                                    <option value="hospedar">Hospedar</option>
                                    <option value="reservar">Reservar</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="fecha_entrada" class="text-sm font-bold">FECHA DE ENTRADA</label>
                                <input type="date" name="fecha_entrada" id="fecha_entrada" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" required>
                            </div>

                            <div class="mb-4">
                                <label for="fecha_salida" class="text-sm font-bold">FECHA DE SALIDA</label>
                                <input type="date" name="fecha_salida" id="fecha_salida" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" required>
                            </div>
                            <div class="mb-4">
                                <label for="cantidad_noches" class="text-sm font-bold">CANTIDAD DE NOCHES</label>
                                <input type="number" name="cantidad_noches" id="cantidad_noches" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" value="0" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="total_a_pagar" class="text-sm font-bold">TOTAL A PAGAR</label>
                                <input type="number" step="0.01" name="total_a_pagar" id="total_a_pagar" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" value="{{$habitacion->precio_por_noche}}" required>
                            </div>

                            <div class="mb-4">
                                <label for="descuento" class="text-sm font-bold">DESCUENTO</label>
                                <input type="number" step="0.01" name="descuento" id="descuento" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" value="0" required>
                            </div>

                            <div class="mb-4">
                                <label for="adelanto" class="text-sm font-bold">ADELANTO</label>
                                <input type="number" step="0.01" name="adelanto" id="adelanto" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" value="0" required>
                            </div>

                            <div class="mb-4 col-span-2">
                                <label for="observaciones" class="text-sm font-bold">OBSERVACIONES</label>
                                <textarea name="observaciones" id="observaciones" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1" placeholder="Escribir algún detalle que tenga el registro"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-center">
                        <a href="{{ route('recepciones') }}" class="bg-danger hover:bg-danger-dark text-white font-bold py-2 px-4 rounded-lg">
                         {{ __('Volver Atrás') }}
                        </a>
                            <x-primary-button class="mr-4">
                                {{ __('Agregar Registro') }}
                            </x-primary-button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addClienteModal" tabindex="-1" role="dialog" aria-labelledby="addClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClienteModalLabel">Agregar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addClienteForm" method="POST" action="{{ route('clientes.store') }}">
                        @csrf

                        <div>
                            <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required autofocus class="mt-1 block w-full" />
                            @error('nombre')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="cedula" class="block font-medium text-sm text-gray-700">Cédula</label>
                            <input type="text" id="cedula" name="cedula" required class="mt-1 block w-full" />
                            @error('cedula')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="correo_electronico" class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
                            <input type="email" id="correo_electronico" name="correo_electronico" required class="mt-1 block w-full" />
                            @error('correo_electronico')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block font-medium text-sm text-gray-700">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" required class="mt-1 block w-full" />
                            @error('telefono')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="direccion" class="block font-medium text-sm text-gray-700">Dirección</label>
                            <input type="text" id="direccion" name="direccion" class="mt-1 block w-full" />
                            @error('direccion')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Crear Cliente') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin del modal -->
</x-app-layout>

<script>
$(document).ready(function() {
    $('#addClienteForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addClienteModal').modal('hide');
                location.reload();
                $('#addClienteForm')[0].reset();
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    alert(value[0]);
                });
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $('#fecha_entrada, #fecha_salida').on('change', function() {
        var fechaEntrada = new Date($('#fecha_entrada').val());
        var fechaSalida = new Date($('#fecha_salida').val());
        var precioPorNoche = parseFloat('{{ $habitacion->precio_por_noche }}');

        // Calcular la diferencia en días
        if (fechaSalida > fechaEntrada) {
            var diferenciaTiempo = fechaSalida - fechaEntrada;
            var cantidadNoches = Math.ceil(diferenciaTiempo / (1000 * 3600 * 24));

            $('#cantidad_noches').val(cantidadNoches);

            // Calcular el total a pagar
            var totalAPagar = cantidadNoches * precioPorNoche;
            $('#total_a_pagar').val(totalAPagar.toFixed(2));
        } else {
            $('#cantidad_noches').val(1);
            $('#total_a_pagar').val(0);
        }
    });
});
</script>
