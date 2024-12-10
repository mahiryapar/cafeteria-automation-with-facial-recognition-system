const video = document.getElementById('videoElement');
const startBtn = document.getElementById('startBtn');
var faceDetected = false;


// Modelleri Yükleme
async function loadModels() {
    await faceapi.nets.ssdMobilenetv1.loadFromUri('../models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('../models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('../models');
    console.log("Modeller Yüklendi.");
}

// Kamera Başlatma
async function startVideo() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
    console.log("Kamera Başlatıldı.");
}
// Yüz Algılama ve Kırpma
async function detectFace() {
    const detections = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
    if (detections) {
        console.log("Yüz algılandı:", detections);
        const faceDescriptor = detections.descriptor;
        return faceDescriptor; // Embedding döndür
    }
    return null;
}

// Embedding Gönderimi
async function sendEmbedding(faceDescriptor) {
    console.log("Embedding:", faceDescriptor);

    // Embedding verisini backend'e gönder
    const formData = new FormData();
    formData.append("embedding", JSON.stringify(faceDescriptor));

    fetch('../backend/yeni_embedding_ekle.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            document.getElementById("sonuc").innerHTML = data;
            const scripts = document.getElementById("sonuc").getElementsByTagName("script");
            for (let script of scripts) {
                eval(script.textContent); 
            }
        })
        .catch(err => console.error("Hata:", err));
}

// Başlatma
startBtn.addEventListener('click', async () => {
    await loadModels();
    await startVideo();

    const interval = setInterval(async () => {
        if (!faceDetected) {
            const faceDescriptor = await detectFace();
            if (faceDescriptor) {
                faceDetected = true;
                clearInterval(interval); // Algılamayı durdur
                console.log("Embedding oluşturuluyor...");
                await sendEmbedding(faceDescriptor).then(() => {
                    console.log("Embedding kaydedildi. Giriş yapabilirsiniz.");
                });
            }
        }
    }, 2000);
});