<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestionar Dispositivos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-2">
                    <a href="{{ route('devices.create') }}" class="text-gray-100 bg-green-600 text-center" style="display: block; width: 100px;">
    {{ __('Agregar Dispositivo') }}
</a>


                        <div class="flex items-center space-x-2">
                            <span class="text-lg font-semibold">Bloquear acceso:</span>
                            <input type="text" id="search" placeholder="Buscar por Nro Habitación" class="mt-1 block w-64 border border-gray-300 rounded-md shadow-sm p-2" onkeyup="searchDevices()">
                        </div>

                    </div>

                    <table class="table table-bordered table-condensed table-striped" id="tabla-habitaciones" style="width: 100%;">
                        <thead style="background-color: #dff1ff;">
                            <tr>
                                <th style="text-align: center;">UUID</th>
                                <th style="text-align: center;">NOMBRE</th>
                                <th style="text-align: center;">TIPO</th>
                                <th style="text-align: center;">DESCRIPCION</th>
                                <th style="text-align: center;">Nro HABITACION</th>
                                <th style="text-align: center;">STATUS</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="devices-body">
                            @foreach($devices as $device)
                                <tr data-uuid="{{ $device->uuid }}" data-habitacion="{{ $device->habitacion ? $device->habitacion->numero_habitacion : 'Sin habitación' }}">
                                    <td style="text-align: center;">{{ $device->uuid }}</td>
                                    <td style="text-align: center;">{{ $device->nombre }}</td>
                                    <td style="text-align: center;">{{ $device->type }}</td>
                                    <td style="text-align: center;">{{ $device->descripcion }}</td>
                                    <td style="text-align: center;">
                                        {{ $device->habitacion ? $device->habitacion->numero_habitacion : 'Sin habitación' }}
                                    </td>
                                    <td class="status-cell px-6 py-4 whitespace-nowrap text-lg text-center {{ $device->status ? 'bg-green-500 text-black' : 'bg-red-500 text-white' }}">
                                        {{ $device->status ? 'Desbloqueado' : 'Bloqueado' }}
                                    </td>
                                    <td style="text-align: center;">
                                    <x-secondary-button onclick="handleClick('{{ $device->uuid }}')" class="status-button">
                                        {{ $device->status ? __('Bloquear') : __('Desbloquear') }}
                                    </x-secondary-button>
                                        <x-custom-button :url="'admin-devices/edit/'" :valor="$device">{{ __('Editar') }}</x-custom-button>
                                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $device->uuid }}')">{{ __('Eliminar') }}</x-danger-button>

                                        <x-modal name='{{ $device->uuid }}' :show="$errors->userDeletion->isNotEmpty()" focusable>
                                            <form method="POST" action="{{ route('devices.destroy', $device->uuid) }}" class="p-6">
                                                @csrf
                                                @method('DELETE')
                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('¿Estás seguro que deseas eliminar el dispositivo ') }}{{ $device->uuid }}{{ __('?') }}
                                                </h2>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancelar') }}</x-secondary-button>
                                                    <x-danger-button class="ms-3">{{ __('Eliminar') }}</x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Agregar paginación -->
                    <div class="mt-4">
                        {{ $devices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function searchDevices() {
        const searchInput = document.getElementById('search').value.toLowerCase();
        const rows = document.querySelectorAll('#devices-body tr');

        rows.forEach(row => {
            const habitacion = row.getAttribute('data-habitacion').toLowerCase();
            if (habitacion.includes(searchInput) || searchInput === '') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    async function handleClick(uuid) {
        const url = '{{ url("api/device/update") }}/' + uuid;
        const button = document.querySelector(`[data-uuid="${uuid}"] .status-button`);
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            console.log('Success:', data);

            if (data.success) {
                const row = document.querySelector(`[data-uuid="${uuid}"]`);
                const statusCell = row.querySelector('.status-cell');
                if (data.new_status) {
                    statusCell.textContent = 'Desbloqueado';
                    statusCell.classList.remove('bg-red-500', 'text-white');
                    statusCell.classList.add('bg-green-500', 'text-black');
                    button.textContent ='Bloquear';
                } else {
                    statusCell.textContent = 'Bloqueado';
                    statusCell.classList.remove('bg-green-500', 'text-black');
                    statusCell.classList.add('bg-red-500', 'text-white');
                    button.textContent ='Desbloquear';
                }
            }

        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>

