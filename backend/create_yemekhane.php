<?php
session_start();
$configPath ='../config/database_infos.json';
if (!file_exists($configPath)) {
    die('Config dosyası bulunamadı.');
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    die('Config dosyası okunamadı veya geçersiz JSON formatı.');
}
$serverName = $config['db_host']; 
$connectionOptions = array(
    "Database" => $config['db_name'], 
    "Uid" => $config['db_user'],    
    "PWD" => $config['db_password'],     
    "CharacterSet"=>"UTF-8"        
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SESSION['role'] !== 'admin') {
    die("Bu işlem için yetkiniz yok.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isim = trim($_POST['isim']);
    $kurum = trim($_POST['kurum']);
    $kapasite = intval(trim($_POST['kapasite']));
    $adres = trim($_POST['adres']);
    $user_id = $_SESSION['user_id'];
    $ogunler = $_POST['ogunler'];

    if (empty($isim) || empty($kurum) || $kapasite <= 0 || empty($adres)) {
        die("Lütfen tüm alanları eksiksiz doldurun.");
    }

    $sql = "INSERT INTO yemekhaneler (isim, kurum, kapasite, adres) OUTPUT INSERTED.id VALUES (?, ?, ?, ?)";
    $params = [$isim, $kurum, $kapasite, $adres];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Yemekhane eklenirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
    }

    $yemekhane_id = null;
    if (sqlsrv_fetch($stmt)) {
        $yemekhane_id = sqlsrv_get_field($stmt, 0);
    }

    if (!$yemekhane_id) {
        die("Yemekhane eklenirken bir hata oluştu.");
    }
    $_SESSION['yemekhane_id'] = $yemekhane_id;
    $update_user_sql = "UPDATE users SET yemekhane_id = ? WHERE id = ?";
    $update_params = [$yemekhane_id, $user_id];
    $update_stmt = sqlsrv_query($conn, $update_user_sql, $update_params);

    if ($update_stmt === false) {
        die("Kullanıcı yemekhane bilgisi güncellenirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
    }
    foreach ($ogunler as $ogun_adi => $ogun_veri) {
        if (isset($ogun_veri['secilen']) && $ogun_veri['secilen'] === '1') {
            $baslangic_saati = $ogun_veri['baslangic'];
            $bitis_saati = $ogun_veri['bitis'];

            if (empty($baslangic_saati) || empty($bitis_saati)) {
                die("$ogun_adi için saatler eksik girildi.");
            }

            $ogun_sql = "INSERT INTO yemekhane_ogunleri (yemekhane_id, ogun, ogun_saati, ogun_bitis_saati) VALUES (?, ?, ?, ?)";
            $ogun_params = [$yemekhane_id, $ogun_adi, $baslangic_saati, $bitis_saati];
            $ogun_stmt = sqlsrv_query($conn, $ogun_sql, $ogun_params);

            if ($ogun_stmt === false) {
                die("$ogun_adi eklenirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
            }
        }
    }

    header("Location: ../pages/yemekhanem.php?message=YemekhaneOlusturuldu");
    exit();
} else {
    die("Geçersiz istek.");
}