var video;
var overlay;
var kapatButonu;
var yuzAlgilamaDurumu;
var startBtn;
let faceDetected = false;

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
                const faceDescriptor = await detectFace();
                if (faceDescriptor) {
                    faceDetected = true;
                    console.log("Embedding oluşturuluyor...");
                    await compareEmbeddings(faceDescriptor);
                    faceDetected = false;
                }
            }
        }, 3000);
    });

    
    kapatButonu.addEventListener('click', () => {
        hideOverlay();
        stopVideo();
    });
    
});




var menu_id;

function deger_al(value){
    menu_id=value;
}


  const showOverlay = () => {
    overlay.style.display = 'block';
};


const hideOverlay = () => {
    overlay.style.display = 'none';
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
        return Infinity; 
    }
    return Math.sqrt(vector1.reduce((sum, val, i) => sum + Math.pow(val - vector2[i], 2), 0));
}

async function compareEmbeddings(newEmbedding) {
    // Veritabanındaki embedding'leri çek
    const response = await fetch('../backend/get_embeddings.php?menu_id='+menu_id);
    const databaseEmbeddings = await response.json();
    // console.log(databaseEmbeddings)
    if (!Array.isArray(databaseEmbeddings) || databaseEmbeddings.length === 0) {
        console.log("Veritabanında hiçbir embedding bulunamadı.");
        return null;
    }
    for (let dbEmbedding of databaseEmbeddings) {
        let dbEmbeddingArray;
        try {
            const parsedEmbedding = JSON.parse(dbEmbedding.embedding);
            dbEmbeddingArray = Object.values(parsedEmbedding);
        } catch (error) {
            console.error("Embedding format hatası:", dbEmbedding.embedding, error);
            continue; 
        }
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




