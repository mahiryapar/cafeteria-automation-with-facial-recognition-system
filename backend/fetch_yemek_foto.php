<?php
  use Google\Cloud\Storage\StorageClient;
function yemekIsminiResimDosyasinaCevir($yemekIsmi) {
    $turkce = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'];
    $ingilizce = ['c', 'g', 'i', 'o', 's', 'u', 'C', 'G', 'I', 'O', 'S', 'U'];
    $yemekIsmi = str_replace($turkce, $ingilizce, $yemekIsmi);
    $yemekIsmi = str_replace(' ', '_', $yemekIsmi);
    $yemekIsmi = strtolower($yemekIsmi);
    return $yemekIsmi;
}
function tumYemekFotolariniGetir($yemekIsimleri) {
    require 'vendor/autoload.php';

    $configPath ='../config/database_infos.json';
    if (!file_exists($configPath)) {
        die('Config dosyası bulunamadı.');
    }
    $config = json_decode(file_get_contents($configPath), true);
    if ($config === null) {
        die('Config dosyası okunamadı veya geçersiz JSON formatı.');
    }

    $projectId = $config["bucket_project_id"];
    $bucketName = $config["bucket_name"];

    if (!file_exists("../config/".$config["bucket_key_name"])) {
        die('Kimlik doğrulama dosyası bulunamadı.');
    }

    putenv("GOOGLE_APPLICATION_CREDENTIALS=../config/".$config["bucket_key_name"]);
    putenv('CURL_CA_BUNDLE=C:\AppServ\php7\extras\ssl\cacert.pem');

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

        $resimler = [];
        foreach ($yemekIsimleri as $yemek) {
            $degistirilmisIsim = yemekIsminiResimDosyasinaCevir($yemek);
            $prefix = "yemekler/" . $degistirilmisIsim;
            $objects = $bucket->objects(['prefix' => $prefix]);
            $object = null;
            foreach ($objects as $obj) {
                $object = $obj;
                break; 
            }

            if ($object && $object->exists()) {
                $contents = $object->downloadAsString();
                $resimler[$yemek] = 'data:image/png;base64,' . base64_encode($contents);
            } else {
                $resimler[$yemek] = "Resim Bulunamadı";
            }
        }
        return $resimler;

    } catch (Exception $e) {
        die('Hata: ' . $e->getMessage());
    }
}

?>