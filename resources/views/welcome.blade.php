<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        .carousel-item {
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
    </style>
    <style>
    .disabled {
        pointer-events: none;
        opacity: 0.8;
    }
</style>
</head>
<body class="bg-gray-100 dark:bg-gray-600">

    <div class="w-full fixed top-0 z-50 bg-white dark:bg-gray-600 shadow-sm">
        <!-- Navbar -->
        <nav class="flex items-center justify-between p-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Hotel Oasis</h1>
            <div class="space-x-6">
                <a href="#servicios" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition duration-200">Servicios</a>
                <a href="#reserva" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition duration-200">Reservar</a>
                <a href="#contacto" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition duration-200">Contacto</a>
            </div>
            @if(Route::has('login'))
            <div class="flex items-center">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" class="text-xl font-bold"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="mr-5 text-lg font-medium dark:text-gray-100 text-true-gray-800 hover:text-cool-gray-700 transition duration-150 ease-in-out">Login</a>
                @endauth
            </div>
            @endif
        </nav>
        <!-- /Navbar -->
    </div>

    <div class="relative w-full h-screen overflow-hidden mt-16">
        <!-- Carrusel de imágenes -->
        <div id="carouselExample" class="carousel slide h-full" data-ride="carousel">
            <div class="carousel-inner h-full">
                <div class="carousel-item active h-full" style="background-image: url('https://oasishoteles.com/_next/image?url=https%3A%2F%2Fspaceohrtest.sfo2.digitaloceanspaces.com%2Fassets%2Fimg%2Fhoteles%2Fgrand-oasis-cancun%2Fheader%2Fheader-goc-full.jpg&w=1920&q=75'); background-size: cover; background-position: center;">
                    <div class="flex flex-col gap-5 bg-black bg-opacity-60 p-6 rounded-lg h-full">
                        <h1 class="text-5xl md:text-7xl font-semibold text-gray-100 leading-none">
                            Bienvenido a Nuestro Hotel
                        </h1>
                        <p class="text-xl font-light text-gray-300 antialiased">
                            Disfruta de una estancia inolvidable con todas las comodidades.
                        </p>
                    </div>
                </div>
                <div class="carousel-item h-full" style="background-image: url('https://vcm.sfo2.cdn.digitaloceanspaces.com/web/images/tours-y-entradas-bg.webp'); background-size: cover; background-position: center;">
                    <div class="flex flex-col gap-5 bg-black bg-opacity-60 p-6 rounded-lg h-full">
                        <h1 class="text-5xl md:text-7xl font-semibold text-gray-100 leading-none">
                            Disfruta de la Comodidad
                        </h1>
                        <p class="text-xl font-light text-gray-300 antialiased">
                            Un lugar perfecto para relajarte y disfrutar.
                        </p>
                    </div>
                </div>
                <div class="carousel-item h-full" style="background-image: url('https://images.unsplash.com/photo-1556740749-887f6717d7e4'); background-size: cover; background-position: center;">
                    <div class="flex flex-col gap-5 bg-black bg-opacity-60 p-6 rounded-lg h-full">
                        <h1 class="text-5xl md:text-7xl font-semibold text-gray-100 leading-none">
                            Experiencia Única
                        </h1>
                        <p class="text-xl font-light text-gray-300 antialiased">
                            Vive momentos inolvidables en nuestro hotel.
                        </p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Siguiente</span>
            </a>
        </div>
        <!-- /Carrusel de imágenes -->
    </div>

    <div class="relative w-full overflow-hidden mt-16">
   <!-- Sección de búsqueda -->
<div class="my-20 flex justify-center" id="reserva">
    <form action="{{ route('buscar.habitaciones') }}" method="GET" class="w-full md:w-1/2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="fecha_inicio" class="block mb-2">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio"
                       class="block w-full p-4 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" required>
            </div>
            <div>
                <label for="fecha_fin" class="block mb-2">Fecha de Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin"
                       class="block w-full p-4 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" required>
            </div>
        </div>
        <button type="submit"
                class="mt-4 bg-red-500 text-white font-medium rounded-lg text-sm px-4 py-2 hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 transition duration-200 ease-in-out">
            Buscar
        </button>
    </form>
</div>

<!-- Sección de resultados -->
@if(isset($habitacionesDisponibles))
    @if($habitacionesDisponibles->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6" id="habitaciones-container">
    @foreach($habitacionesDisponibles as $habitacion)
        <div class="disabled">
            <x-recepcion-create-card :habitacion="$habitacion" />
        </div>
    @endforeach
</div>
    @else
        <div class="mt-6 text-center text-red-600">
            No hay habitaciones disponibles para las fechas seleccionadas.
        </div>
    @endif
@endif




        <!-- Sección de servicios -->
        <div class="my-20 py-12 bg-white dark:bg-gray-800 rounded-xl mx-4 md:mx-0 shadow-lg" id="servicios">
            <h2 class="text-3xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100 text-center">
                Nuestros Servicios
            </h2>
            <div class="grid md:grid-cols-3 gap-8 mt-12">
                <div class="flex flex-col items-center text-center bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h18v18H3V3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Habitaciones Confortables
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">
                        Disfruta de habitaciones equipadas con todas las comodidades para una estancia placentera.
                    </p>
                </div>
                <div class="flex flex-col items-center text-center bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Cliente 24/7
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">
                        Nuestro equipo está disponible las 24 horas para atender tus necesidades y preguntas.
                    </p>
                </div>
                <div class="flex flex-col items-center text-center bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-center w-16 h-16 bg-red-600 text-white rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10v4a1 1 0 001 1h16a1 1 0 001-1v-4M3 10a1 1 0 011-1h16a1 1 0 011 1m-2-4h2a1 1 0 011 1v2M3 6h2a1 1 0 011 1v2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Restaurante Gourmet
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">
                        Saborea deliciosos platillos preparados por chefs de renombre.
                    </p>
                </div>
            </div>
        </div>
        <!-- /Sección de servicios -->

        <!-- Sección de galería -->
        <div class="my-20 py-12 bg-white dark:bg-gray-800 rounded-xl mx-4 md:mx-0 shadow-lg" id="galeria">
            <h2 class="text-3xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100 text-center">
                Galería de Habitaciones
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <img src="https://oasishoteles.com/_next/image?url=https%3A%2F%2Fspaceohrtest.sfo2.digitaloceanspaces.com%2Fassets%2Fimg%2Fcancun%2Fhoteles%2Fgrand-oasis-cancun%2Fhabitaciones%2Fgrand-ocean-view%2F02.jpg&w=1920&q=75" alt="Habitación 1" class="w-full rounded-lg shadow-md">
                <img src="https://oasishoteles.com/_next/image?url=https%3A%2F%2Fspaceohrtest.sfo2.digitaloceanspaces.com%2Fassets%2Fimg%2Fcancun%2Fhoteles%2Fgrand-oasis-cancun%2Fhabitaciones%2Fgrand-standard%2F01.jpg&w=828&q=75" alt="Habitación 2" class="w-full rounded-lg shadow-md">
                <img src="https://oasishoteles.com/_next/image?url=https%3A%2F%2Fspaceohrtest.sfo2.digitaloceanspaces.com%2Fassets%2Fimg%2Fcancun%2Fhoteles%2Fgrand-oasis-cancun%2Fhabitaciones%2Fgrand-sunset%2F01.jpg&w=640&q=75" alt="Habitación 3" class="w-full rounded-lg shadow-md">
                </div>
        </div>
        <!-- /Sección de galería -->
    </div>
<!-- Sección de contacto -->
<div class="my-20 py-12 bg-white dark:bg-gray-800 rounded-xl mx-4 md:mx-0 shadow-lg" id="contacto">
    <h2 class="text-3xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100 text-center">
        Contáctanos
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
        <div class="flex flex-col">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Formulario de Contacto</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                Si tienes alguna pregunta, no dudes en enviarnos un mensaje. Te responderemos lo más pronto posible.
            </p>
            <form class="mt-4">
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required
                           class="block w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500" placeholder="Tu nombre">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required
                           class="block w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500" placeholder="Tu correo electrónico">
                </div>
                <div class="mb-4">
                    <label for="mensaje" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="4" required
                              class="block w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500" placeholder="Escribe tu mensaje aquí..."></textarea>
                </div>
                <button
                        class="bg-blue-600 text-white font-semibold rounded-lg px-4 py-2 hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 transition duration-200 ease-in-out">
                    Enviar Mensaje
                </button>
            </form>
        </div>
        <div class="flex flex-col">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Información de Contacto</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                Puedes contactarnos a través de los siguientes medios:
            </p>
            <ul class="mt-4 space-y-2">
                <li class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h18v18H3V3z" />
                    </svg>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Teléfono: +591 78149868</span>
                </li>
                <li class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h18v18H3V3z" />
                    </svg>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Correo: henry@hoteliot.com</span>
                </li>
                <li class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h18v18H3V3z" />
                    </svg>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Av. Brasil, Santa Cruz, Bolivia</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sección de contacto -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        let carousel = document.querySelector('#carouselExample');
        setInterval(() => {
            const next = carousel.querySelector('.carousel-control-next');
            next.click();
        }, 5000);
    </script>
</body>
</html>
