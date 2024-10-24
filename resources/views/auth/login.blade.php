<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Contenedor para la carga -->
    <div id="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div class="spinner"></div> <!-- Puedes añadir un spinner aquí -->
        <p>Cargando...</p>
    </div>

    <!-- Contenedor para la cámara y el canvas -->
    <div id="container" style="position: relative; display: none;">
        <video id="video" width="640" height="480" autoplay muted></video>
        <canvas id="canvas" style="display: none;"></canvas>
    </div>
    <!-- Div oculto que contiene todos los usuarios -->
    <div id="hidden-users" style="display: none;">
        @json($users)
    </div>
    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>

            <!-- Interruptor para Iniciar Sesión con Cámara -->
            <div class="flex items-center ms-3">
                <input type="checkbox" id="camera-switch" class="rounded dark:bg-gray-900 border-gray-900 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-900" />
                <label for="camera-switch" class="ml-2 text-lm text-gray-600 dark:text-gray-400">{{ __('Log in with Camera') }}</label>
            </div>
        </div>
    </form>

    <script>
         const allusers = JSON.parse(document.getElementById('hidden-users').innerHTML); // Cargar usuarios desde el div oculto
       // console.log(users); // Para verificar que los usuarios se están cargando correctamente
        const users = allusers.map(user => user.name);
        const labels = users;
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let labeledFaceDescriptors = [];

        document.getElementById('camera-switch').addEventListener('change', async (event) => {
            const container = document.getElementById('container');
            if (event.target.checked) {
                await startCamera();
                container.style.display = 'block';
                canvas.style.display = 'block';
            } else {
                stopCamera();
                container.style.display = 'none';
                canvas.style.display = 'none';
            }
        });

        async function loadLabeledImages() {
            document.getElementById('loading').style.display = 'flex';

            const labels = users;
            const labeledFaceDescriptors = await Promise.all(
                labels.map(async (label) => {
                    const descriptions = [];
                    for (let i = 1; i <= 4; i++) {
                        const img = await faceapi.fetchImage(`images/${label}/${i}.jpg`);
                        const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                        if (detections) {
                            descriptions.push(detections.descriptor);
                        }
                    }
                    return new faceapi.LabeledFaceDescriptors(label, descriptions);
                })
            );

            document.getElementById('loading').style.display = 'none';
            return labeledFaceDescriptors;
        }

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {}
                });
                video.srcObject = stream;
            } catch (err) {
                console.error('Error al acceder a la cámara: ', err);
            }
        }

        function stopCamera() {
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        let isRequestInProgress = false;

        function enviarDatos(decodeText) {
            if (isRequestInProgress) {
                console.log('Ya hay una solicitud en proceso, por favor espera...');
                return;
            }


            const regex = /(\w+)\s*\(([\d.]+)\)/;
            const match = decodeText.match(regex);
            let user = "";
            let confianza = 0;

            if (match) {
                user = match[1];
                confianza = parseFloat(match[2]);
            } else {
                console.log("No se encontró un patrón coincidente.");
            }

            if (users.includes(user.trim()) && confianza > 0.55) {
                console.log('user:', user.trim());
                console.log('confianza:', confianza);

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                isRequestInProgress = true;

                axios.post('/login-ia/auth', {
                        user: user.trim(),
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        console.log('Datos enviados correctamente:', response.data);
                        if (response.data.redirect) {
                            window.location.href = response.data.redirect;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.response.data.message);
                    })
                    .finally(() => {
                        isRequestInProgress = false;
                    });
            } else {
                console.error('Error: usuario no válido o confianza insuficiente');
            }
        }

        async function loadModels() {
            document.getElementById('loading').style.display = 'flex';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                faceapi.nets.faceExpressionNet.loadFromUri('/models'),
                faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
            ]);
            document.getElementById('loading').style.display = 'none';
        }

        (async () => {
            await loadModels();
            labeledFaceDescriptors = await loadLabeledImages();
            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);


            video.addEventListener('play', async () => {

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.style.display = 'block';
                const displaySize = {
                    width: (video.videoWidth / 2) + 75,
                    height: (video.videoHeight / 2) + 60
                };
                faceapi.matchDimensions(canvas, displaySize);
                console.log('video is playing');

                const detectFace = async () => {
                    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptors()
                        .withFaceExpressions();

                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

                    resizedDetections.forEach((detection, i) => {
                        const box = detection.detection.box;
                        const label = results[i].toString();
                        console.log("label: " + label);
                        ctx.strokeStyle = 'green';
                        ctx.lineWidth = 2;
                        ctx.strokeRect(box.x, box.y, box.width, box.height);
                        ctx.fillStyle = 'white';
                        ctx.fillText(label, box.x, box.y > 10 ? box.y - 5 : 10);

                        enviarDatos(label);
                    });
                };

                setInterval(detectFace, 100);
            });
        })().catch(err => {
            console.error('Error al cargar los modelos o las imágenes:', err);
            document.getElementById('loading').style.display = 'none';
        });
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
    <style>
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
        }
    </style>
</x-guest-layout>
