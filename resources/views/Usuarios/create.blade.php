<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('CREAR USUARIO') }}
        </h2>
    </x-slot>

    <x-guest-layout>
     <!-- Contenedor para la carga -->
     <div id="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div class="spinner"></div> <!-- Puedes añadir un spinner aquí -->
        <p>Capturando fotos...</p>
    </div>
    <div id="hidden-users" style="display: none;">
        @json($users)
    </div>
        <div id="container" style="position: relative; display: none;">
            <video id="video" width="640" height="480" autoplay muted></video>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>
        <form method="POST" action="{{ route('admin.users.register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Hidden fields for images -->
            <input type="hidden" id="photo1" name="photos[]" />
            <input type="hidden" id="photo2" name="photos[]" />
            <input type="hidden" id="photo3" name="photos[]" />
            <input type="hidden" id="photo4" name="photos[]" />

            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="ms-4" onclick="capturePhotos(event)">
                    {{ __('Registrar') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
</x-app-layout>

<script>
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            document.getElementById('video').srcObject = stream;
            document.getElementById('container').style.display = 'block';
        } catch (err) {
            console.error('Error al acceder a la cámara: ', err);
        }
    }

    async function capturePhotos(event) {

        event.preventDefault(); // Evitar el envío inmediato del formulario
        const allusers = JSON.parse(document.getElementById('hidden-users').innerHTML);
        const userNames = allusers.map(user => user.name.toLowerCase());
        const userEmails = allusers.map(user => user.email.toLowerCase());

        const name = document.getElementById('name').value.toLowerCase(); // Obtener el nombre en minúsculas
        const email = document.getElementById('email').value.toLowerCase(); // Obtener el email en minúsculas

        // Comprobar si el nombre ya existe
        if (userNames.includes(name)) {
            alert("El usuario ya existe. Por favor, elige otro nombre.");
            return; // Salir de la función si el usuario ya existe
        }

        // Comprobar si el email ya existe
        if (userEmails.includes(email)) {
            alert("El correo electrónico ya está registrado. Por favor, utiliza otro email.");
            return; // Salir de la función si el email ya existe
        }

        const canvas = document.getElementById('canvas');
        const video = document.getElementById('video');
        const photos = [];

        // Mostrar el indicador de carga
        document.getElementById('loading').style.display = 'flex';

        for (let i = 0; i < 4; i++) {
            await new Promise(resolve => setTimeout(resolve, 1000)); // Espera 1 segundo entre capturas
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const photoData = canvas.toDataURL('image/jpg'); // Captura la imagen
            photos.push(photoData);
            console.log(`Foto ${i + 1}:`, photoData); // Mostrar en consola la foto capturada
        }

        // Asignar las imágenes a los campos ocultos
        document.getElementById('photo1').value = photos[0];
        document.getElementById('photo2').value = photos[1];
        document.getElementById('photo3').value = photos[2];
        document.getElementById('photo4').value = photos[3];

        // Ocultar el indicador de carga y enviar el formulario
        document.getElementById('loading').style.display = 'none';
        event.target.form.submit();
    }

    // Inicia la cámara al cargar la página
    window.onload = startCamera;
</script>
<style>
        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

