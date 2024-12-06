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


if (!isset($_GET['user_id']) || empty(trim($_GET['user_id']))) {
    die("Geçersiz kullanıcı ID.");
}

$user_id = intval(trim($_GET['user_id']));


$yemekhane_id = $_SESSION['yemekhane_id'];


$sql_check = "SELECT * FROM users WHERE id = ? AND yemekhane_id = ?";
$params_check = [$user_id, $yemekhane_id];
$stmt_check = sqlsrv_query($conn, $sql_check, $params_check);

if ($stmt_check === false || !($ogrenci = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC))) {
    die("Öğrenci bu yemekhaneye bağlı değil veya kullanıcı bulunamadı.");
}


$sql_update = "UPDATE users SET yemekhane_id = 1 WHERE id = ?";
$params_update = [$user_id];
$stmt_update = sqlsrv_query($conn, $sql_update,$params_update);

if ($stmt_update === false) {
    die("Öğrenci kaldırılırken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
}

header("Location: ../pages/yemekhanem.php?message=OgrenciKaldirildi");
exit;
?>