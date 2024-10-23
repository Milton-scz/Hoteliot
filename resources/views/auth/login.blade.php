<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Contenedor para la cámara y el canvas -->
    <!-- Contenedor para la cámara y el canvas -->
    <div id="container" style="position: relative; display: none;"> <!-- Inicialmente oculto -->
        <video id="video" width="640" height="480" autoplay muted></video>
        <canvas id="canvas" style="display: none;"></canvas>
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
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d'); // Mueve esto fuera para poder usarlo más tarde
        let labeledFaceDescriptors = []; // Almacenará las descripciones de las caras

        document.getElementById('camera-switch').addEventListener('change', async (event) => {
            const container = document.getElementById('container');
            if (event.target.checked) {
                await startCamera();
                container.style.display = 'block';
                canvas.style.display = 'block'; // Hacemos visible el canvas
            } else {
                stopCamera(); // Detiene la cámara si no se muestra
                container.style.display = 'none';
                canvas.style.display = 'none'; // Ocultar el canvas
            }
        });
        async function loadLabeledImages() {
            const labels = ['henry','pablo','jhordan','melina']; // Cambia estos nombres según tus necesidades
            return Promise.all(
                labels.map(async (label) => {
                    const descriptions = []; // Esta lista contendrá las descripciones de las caras
                    for (let i = 1; i <= 4; i++) { // Supongamos que tienes 2 imágenes por persona
                        const img = await faceapi.fetchImage(`images/${label}/${i}.jpg`);
                        const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                        if (detections) {
                            descriptions.push(detections.descriptor); // Almacena el descriptor solo si se detecta una cara
                        }
                    }
                    return new faceapi.LabeledFaceDescriptors(label, descriptions); // Crea un nuevo LabeledFaceDescriptors
                })
            );
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
                video.srcObject = null; // Libera el video
            }
        }

        let isRequestInProgress = false;

        function enviarDatos(decodeText) {
            if (isRequestInProgress) {
                console.log('Ya hay una solicitud en proceso, por favor espera...');
                return;
            }

            const users = [ "henry","pablo","alexander","melina","jhordan"];
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
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                faceapi.nets.faceExpressionNet.loadFromUri('/models'),
                faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
            ]);
        }

        (async () => {
            await loadModels(); // Espera a que se carguen todos los modelos
            labeledFaceDescriptors = await loadLabeledImages(); // Carga las imágenes etiquetadas
            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
            //await startCamera(); // Asegúrate de que la cámara se inicie después de cargar los modelos

            video.addEventListener('play', async () => {
                // Establece el tamaño del canvas igual al del video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.style.display = 'block'; // Hacemos visible el canvas
                const displaySize = {
                    width: (video.videoWidth / 2) + 75,
                    height: (video.videoHeight / 2) + 60
                };
                faceapi.matchDimensions(canvas, displaySize);

                setInterval(async () => {
                    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptors()
                        .withFaceExpressions(); // Asegúrate de incluir las expresiones

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
                        ctx.fillText(label, box.x, box.y > 10 ? box.y - 5 : 10); // Dibuja el nombre encima del cuadro

                        enviarDatos(label);
                    });

                    // Dibuja los landmarks y expresiones
                    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                    faceapi.draw.drawFaceExpressions(canvas, resizedDetections); // Esto ahora debería funcionar sin errores
                }, 100);
            });

        })().catch(err => console.error('Error al cargar los modelos o las imágenes:', err));
    </script>

    <style>
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            /* Para que el canvas esté sobre el video */
        }
    </style>
</x-guest-layout>
