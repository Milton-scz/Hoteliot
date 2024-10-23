<!-- resources/views/habitaciones/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestionar Recepcion') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
                <label for="status-filter" class="block mb-2 font-bold">Filtrar por estado:</label>
                <select id="status-filter" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="todos">Todos</option>
                    <option value="disponible">Disponibles</option>
                    <option value="ocupado">Ocupados</option>
                    <option value="reservado">Reservados</option>
                </select>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                <div class="grid grid-cols-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-6" id="habitaciones-container">
                @foreach($habitaciones as $habitacion)
                        <div class="habitacion-card" data-status="{{ $habitacion->estado }}">
                            <x-recepcion-create-card :habitacion="$habitacion" />
                        </div>
                    @endforeach
                </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    // Función para filtrar las habitaciones
    document.getElementById('status-filter').addEventListener('change', filterHabitaciones);

    function filterHabitaciones() {
        const selectedState = document.getElementById('status-filter').value;

        // Obtener todas las tarjetas de habitaciones
        const cards = document.querySelectorAll('#habitaciones-container .habitacion-card');

        cards.forEach(card => {
            const estado = card.getAttribute('data-status');

            // Mostrar todas las habitaciones si "todos" está seleccionado
            if (selectedState === 'todos' || estado === selectedState) {
                card.style.display = ''; // Mostrar tarjeta
            } else {
                card.style.display = 'none'; // Ocultar tarjeta
            }
        });
    }
</script>
