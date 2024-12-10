var video;
var overlay;
var kapatButonu;
var yuzAlgilamaDurumu;
var startBtn;
let faceDetected = false; // Yüz algılandı mı kontrolü için

document.addEventListener('DOMContentLoaded', () => {
    overlay = document.getElementById('overlay');
    kapatButonu = document.getElementById('kapatButonu');
    yuzAlgilamaDurumu = document.getElementById('yuzAlgilamaDurumu');
    video = document.getElementById('videoElement');
    startBtn = document.getElementById('startBtn');

    
    startBtn.addEventListener('click', async () => {
        showOverlay();
        await loadModels();
        await startVideo();
    
        const interval = setInterval(async () => {
            if (!faceDetected) {
                faceDetected=true;
                const faceDescriptor = await detectFace();
                if (faceDescriptor) {
                    faceDetected = true;
                    clearInterval(interval); // Algılamayı durdur
                    console.log("Embedding oluşturuluyor...");
                    await compareEmbeddings(faceDescriptor);
                    faceDetected = false;
                }
            }
        }, 2000);
    });

    // Kapat butonuna tıklama olayı
    kapatButonu.addEventListener('click', () => {
        hideOverlay(); // Overlay'i gizle
        stopVideo(); // Videoyu durdur
    });
    // Video akışını durdur
    
});




var menu_id;
// let faceDetected = false;
function deger_al(value){
    menu_id=value;
}

  // Overlay'i açan işlev
  const showOverlay = () => {
    overlay.style.display = 'block'; // Overlay'i göster
};

// Overlay'i kapatan işlev
const hideOverlay = () => {
    overlay.style.display = 'none'; // Overlay'i gizle
};


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

const stopVideo = () => {
    const stream = video.srcObject;
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());
        video.srcObject = null;
    }
};

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
    const response = await fetch('../backend/get_embeddings.php?menu_id='+menu_id);
    const databaseEmbeddings = await response.json();
    console.log(databaseEmbeddings)
    if (!Array.isArray(databaseEmbeddings) || databaseEmbeddings.length === 0) {
        console.log("Veritabanında hiçbir embedding bulunamadı.");
        return null;
    }
    console.log("Veritabanından alınan embedding'ler:", databaseEmbeddings);
    for (let dbEmbedding of databaseEmbeddings) {
        console.log("Orijinal format:", dbEmbedding.embedding);
        let dbEmbeddingArray;
        try {
            const parsedEmbedding = JSON.parse(dbEmbedding.embedding); // Parse the string
            dbEmbeddingArray = Object.values(parsedEmbedding);
        } catch (error) {
            console.error("Embedding format hatası:", dbEmbedding.embedding, error);
            continue; // Bu embedding'i atla
        }
        console.log("Diziye dönüştürülmüş format:", dbEmbeddingArray, "Uzunluk:", dbEmbeddingArray.length);
        const distance = euclideanDistance(newEmbedding, dbEmbeddingArray);
        if (distance < 0.5) { 
            console.log("Eşleşme bulundu: ", dbEmbedding.user);
            try {
                const response = await fetch(`../backend/embedding_menu_kontrol.php?menu_id=${menu_id}&user_id=${dbEmbedding.user_id}`);
                const result = await response.json();
    
                const infoDiv = document.getElementById("info_div");
                if (result.status === "yendi") {
                    infoDiv.innerHTML = `<p>${result.message}</p>`;
                } else if (result.status === "yeni_yendi") {
                    infoDiv.innerHTML = `<p>${result.message}</p>`;
                } else if (result.status === "yok") {
                    infoDiv.innerHTML = `<p>${result.message}</p>`;
                } else {
                    infoDiv.innerHTML = `<p>Bilinmeyen durum.</p>`;
                }
            } catch (error) {
                console.error("Hata:", error);
            }
            return;
        }
    }
    return null;
}




