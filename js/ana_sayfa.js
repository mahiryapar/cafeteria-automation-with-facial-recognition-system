const yemekBaslatButonu = document.getElementById('yemekBaslatButonu');
const overlay = document.getElementById('overlay');
const video = document.getElementById('videoElement');
const kapatButonu = document.getElementById('kapatButonu');

const startBtn = document.getElementById('startBtn');



// Yüz algılama modelini yükle
async function loadBlazeFaceModel() {
    const model = await blazeface.load();
    console.log("BlazeFace Modeli Yüklendi.");
    return model;
}

async function startVideo() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
    video.srcObject = stream;
}


async function detectFace(model) {
    const returnTensors = false;
    const predictions = await model.estimateFaces(video, returnTensors);

    if (predictions.length > 0) {
        console.log("Yüz algılandı:", predictions);
        captureAndSend();
    } else {
        console.log("Yüz algılanamadı.");
    }
}

async function captureAndSend() {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageData = canvas.toDataURL('image/jpeg');
    const blob = dataURItoBlob(imageData);

    const formData = new FormData();
    formData.append('image', blob, 'image.jpg');

    // PHP'ye gönder
    fetch('../backend/process_face.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        console.log(result.message); // Başarı durumunu konsola yazdır
    })
    .catch(error => console.log('Hata:', error));
}

function dataURItoBlob(dataURI) {
    const byteString = atob(dataURI.split(',')[1]);
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], { type: 'image/jpeg' });
}

// Yüz tanıma işlevini başlat
startBtn.addEventListener('click', async () => {
    const model = await loadBlazeFaceModel();
    startVideo();
    setInterval(() => detectFace(model), 1000); // Yüz algılamayı her saniye kontrol et
});


yemekBaslatButonu?.addEventListener('click', () => {
    overlay.style.display = 'block';

    // Kamera açma işlemi
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((stream) => {
                video.srcObject = stream;
            })
            .catch((err) => {
                console.error('Kamera açılırken bir hata oluştu:', err);
            });
    } else {
        alert('Tarayıcınız kamera desteği sağlamıyor.');
    }
});

kapatButonu?.addEventListener('click', () => {
    overlay.style.display = 'none';
    const stream = video.srcObject;
    const tracks = stream?.getTracks();

    // Kamera akışını durdurma
    tracks?.forEach(track => track.stop());
    video.srcObject = null;
});