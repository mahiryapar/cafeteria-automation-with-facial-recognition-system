const video = document.getElementById('videoElement');
const startBtn = document.getElementById('startBtn');
var faceDetected = false;

// BlazeFace Modelini Yükle
async function loadBlazeFaceModel() {
    const model = await blazeface.load();
    console.log("BlazeFace Modeli Yüklendi.");
    return model;
}

// FaceNet Modelini Yükle
async function loadFaceNetModel() {
    const model = await tf.loadGraphModel("https://tfhub.dev/google/tfjs-model/facenet/1/default/1");
    console.log("FaceNet Modeli Yüklendi.");
    return model;
}

// Kamera Başlatma
async function startVideo() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
}

// Yüz Algılama
async function detectFace(model) {
    const predictions = await model.estimateFaces(video, false);
    if (predictions.length > 0) {
        console.log("Yüz algılandı:", predictions);
        return captureFace(predictions[0]); // Yüz kırpıldıktan sonra gönder
    }
    return null;
}

// Yüzü Kırp ve Gönder
function captureFace(prediction) {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    const [startX, startY, endX, endY] = prediction.topLeft.concat(prediction.bottomRight);
    const width = endX - startX;
    const height = endY - startY;

    ctx.drawImage(video, startX, startY, width, height, 0, 0, width, height);
    return tf.browser.fromPixels(canvas); // Tensor olarak döndür
}

// Embedding Gönderimi
async function sendEmbedding(faceTensor, faceNetModel) {
    const embedding = faceNetModel.predict(faceTensor.expandDims(0)); // Embedding oluştur
    const embeddingArray = await embedding.array(); // Float32Array'e çevir

    console.log("Embedding:", embeddingArray);

    // Embedding verisini backend'e gönder
    const formData = new FormData();
    formData.append("embedding", JSON.stringify(embeddingArray));

    fetch('../backend/yeni_embedding_ekle.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("sonuc").innerHTML = data;
        console.log(data);
    })
    .catch(err => console.error("Hata:", err));
}

// Başlatma
startBtn.addEventListener('click', async () => {
    const blazeFaceModel = await loadBlazeFaceModel();
    const faceNetModel = await loadFaceNetModel();
    await startVideo();

    const interval = setInterval(async () => {
        if (!faceDetected) {
            const faceTensor = await detectFace(blazeFaceModel);
            if (faceTensor) {
                faceDetected = true; // Yüz algılandı
                clearInterval(interval); // Algılamayı durdur
                console.log("Embedding oluşturuluyor...");
                sendEmbedding(faceTensor, faceNetModel).then(() => {
                    console.log("Embedding kaydedildi. Giriş yapabilirsiniz.");
                });
            }
        }
    }, 2000);
});