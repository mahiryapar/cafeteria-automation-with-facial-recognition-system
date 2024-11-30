<?php
require 'vendor/autoload.php';
session_start();
use Google\Cloud\Storage\StorageClient;

if (isset($_SESSION['nickname'])) {
    $nickname = $_SESSION['nickname'];
} else {
    die("Kullanıcı oturumu bulunamadı.");
}
$configPath ='../config/database_infos.json';
if (!file_exists($configPath)) {
    die('Config dosyası bulunamadı.');
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    die('Config dosyası okunamadı veya geçersiz JSON formatı.');
}

$projectId = $config['bucket_project_id'];
$bucketName = $config['bucket_name'];
$prefix = "photos/" . $nickname;

if (!file_exists("../config/".$config['bucket_key_name'])) {
    die('Kimlik doğrulama dosyası bulunamadı.');
}

putenv("GOOGLE_APPLICATION_CREDENTIALS=../config/".$config['bucket_key_name']);
putenv('CURL_CA_BUNDLE=C:\AppServ\php7\extras\ssl\cacert.pem');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $storage = new StorageClient([
            'projectId' => $projectId,
            'httpOptions' => [
                'verify' => false, 
            ]
        ]);
        $bucket = $storage->bucket($bucketName);
        if (!$bucket->exists()) {
            die('Bucket bulunamadı.');
        }
        $objects = $bucket->objects(['prefix' => $prefix]);
        $object = null;
        foreach ($objects as $obj) {
            $object = $obj;
            break;
        }
        if ($object && $object->exists()) {
            $object->delete();
            include 'fetch_image.php';
            header("Location: ../pages/hesabim.php");
        } else {
            include 'fetch_image.php';
            header("Location: ../pages/hesabim.php");
        }
        } catch (Exception $e) {
            die('Hata: ' . $e->getMessage());
        }
} else {
    echo "Silme işlemi gönderilmedi.";
}
?>