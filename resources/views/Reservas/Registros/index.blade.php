<x-app-layout>
    <x-slot name="header" class="mt-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('GESTIONAR REGISTROS / SMART CONTRACT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section id="contenido_principal">
                        <div class="col-md-12" style="margin-top: 10px;">

                        </div>

                        <div class="col-md-12">
                            <div class="box box-default" style="border: 1px solid #0c0c0c;">
                                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="padding: 10px;">
                                    <div style="height: 100%; overflow: auto;">
                                        <table class="table table-bordered table-condensed table-striped"
                                            id="tabla-empresas" style="width: 100%;">
                                            <!-- Encabezados de la tabla -->
                                            <thead>
                                                <th colspan="5"></th>
                                            </thead>
                                            <thead style="background-color: #dff1ff;">
                                                <th style="text-align: center;">Nro</th>
                                                <th style="text-align: center;">trxhash</th>
                                                <th style="text-align: center;">Acción</th>
                                            </thead>
                                            @foreach($registros as $registro)

                                                <tr>
                                                    <td style="text-align: center;">{{$registro->id}}</td>
                                                    <td style="text-align: center;">{{$registro->trxhash}}</td>
                                                    <td style="text-align: center;">
                                                    <td style="text-align: center; padding: 10px;">
                                                    <a href="https://sepolia.scrollscan.com/tx/{{ $registro->trxhash }}" target="_blank" style="text-decoration: none; color: white; background-color: green; padding: 5px 10px; border-radius: 5px;">
                                                        {{ __('Ver detalle') }}
                                                    </a>
                                                </td>
                                                    </td>
                                                </tr>

                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="my-4">
                                    {{$registros->links()}}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
<!--MODAL PARA ELIMINAR-->
