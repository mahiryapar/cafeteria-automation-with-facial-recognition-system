const video = document.getElementById('videoElement');
// const kapatButonu = document.getElementById('kapatButonu');
// const startBtn = document.getElementById('startBtn');
var menu_id;
// let faceDetected = false;
function deger_al(value){
    menu_id=value;
}

async function loadModels() {
    await faceapi.nets.ssdMobilenetv1.loadFromUri('../models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('../models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('../models');
    console.log("Modeller Yüklendi.");
}

async function startVideo() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
    console.log("Kamera Başlatıldı.");
}

async function detectFace() {
    const detections = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
    if (detections) {
        console.log("Yüz algılandı:", detections);
        const faceDescriptor = detections.descriptor;
        console.log(faceDescriptor)
        return faceDescriptor;
    }
    return null;
}

function euclideanDistance(vector1, vector2) {
    if (!vector1 || !vector2 || vector1.length !== vector2.length) {
        console.error("Invalid vectors for Euclidean distance calculation:", vector1, vector2);
        return Infinity; // Or another appropriate default value, like -1 or throw an error.
    }
    return Math.sqrt(vector1.reduce((sum, val, i) => sum + Math.pow(val - vector2[i], 2), 0));
}

async function compareEmbeddings(newEmbedding) {
    // Veritabanındaki embedding'leri çek
    const response = await fetch("../backend/get_embeddings.php?menu_id="+menu_id);
    const databaseEmbeddings = await response.json();
    console.log(databaseEmbeddings);
    if (!Array.isArray(databaseEmbeddings) || databaseEmbeddings.length === 0) {
        console.log("Veritabanında hiçbir embedding bulunamadı.");
        return null;
    }
    console.log("Veritabanından alınan embedding'ler:", databaseEmbeddings);
    for (let dbEmbedding of databaseEmbeddings) {
        console.log("Karşılaştırma yapılacak embedding'ler:", newEmbedding, dbEmbedding.embedding);
        console.log(newEmbedding, dbEmbedding.embedding, newEmbedding.length, dbEmbedding.embedding.length)
        const distance = euclideanDistance(newEmbedding, dbEmbedding.embedding);
        if (distance < 0.6) { 
            console.log("Eşleşme bulundu: ", dbEmbedding.user);
            return dbEmbedding.user;
        }
    }
    return null;
}

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
                const matchedUser = await compareEmbeddings(faceDescriptor);
                if (matchedUser) {
                    console.log("Kullanıcı bulundu:", matchedUser);
                } else {
                    console.log("Eşleşen kullanıcı bulunamadı.");
                }
            }
        }
    }, 2000);
});
