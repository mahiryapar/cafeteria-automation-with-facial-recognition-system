<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Resim dosyasını al
    $file = $_FILES['image'];

    // Geçici dosya yolu
    $tmpFilePath = $file['tmp_name'];

    // Hedef dizin ve dosya adı
    $targetDir = '../algilananresimler/';
    $targetFilePath = $targetDir . basename($file['name']);

    // Hedef dizin var mı kontrol et, yoksa oluştur
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Dosyayı hedef dizine taşı
    if (move_uploaded_file($tmpFilePath, $targetFilePath)) {
        $response = [
            'status' => 'success',
            'message' => 'Resim başarıyla yüklendi.',
            'file_path' => $targetFilePath
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Resim yüklenirken bir hata oluştu.'
        ];
    }

    // JSON formatında yanıt ver
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Hatalı istek durumu
    header("HTTP/1.1 400 Bad Request");
    $response = [
        'status' => 'error',
        'message' => 'Geçersiz istek.'
    ];
    echo json_encode($response);
}
?>
