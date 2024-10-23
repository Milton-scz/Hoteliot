<script>
async function initializeFaceRecognition(startCamera, video) {
    //const video = document.getElementById('video');
    let labeledFaceDescriptors = [];

    async function loadLabeledImages() {
        const labels = ['milton']; // Cambia estos nombres según tus necesidades
        return Promise.all(
            labels.map(async (label) => {
                const descriptions = [];
                for (let i = 1; i <= 2; i++) { // Supongamos que tienes 2 imágenes por persona
                    const img = await faceapi.fetchImage(`/images/${label}/${i}.jpg`);
                    const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                    if (detections) {
                        descriptions.push(detections.descriptor);
                    }
                }
                return new faceapi.LabeledFaceDescriptors(label, descriptions);
            })
        );
    }

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            video.srcObject = stream;
            return stream; // Devolvemos el flujo para poder detenerlo después
        } catch (err) {
            console.error('Error al acceder a la cámara: ', err);
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

    await loadModels();
    labeledFaceDescriptors = await loadLabeledImages();
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

    if (startCamera) {
        const stream = await startCamera(); // Iniciar la cámara y obtener el flujo
        video.addEventListener('play', async () => {
            const canvas = faceapi.createCanvasFromMedia(video);
            document.body.append(canvas);
            const displaySize = { width: video.videoWidth, height: video.videoHeight };
            faceapi.matchDimensions(canvas, displaySize);

            setInterval(async () => {
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptors()
                    .withFaceExpressions();

                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

                resizedDetections.forEach((detection, i) => {
                    const box = detection.detection.box;
                    const label = results[i].toString();
                    console.log('Detecciones:',label);

                    const ctx = canvas.getContext('2d');
                    ctx.strokeStyle = 'blue';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);
                    ctx.fillStyle = 'blue';
                    ctx.fillText(label, box.x, box.y > 10 ? box.y - 5 : 10);
                });

                faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                faceapi.draw.drawFaceExpressions(canvas, resizedDetections);
            }, 100);
        });
    }
}

</script>
