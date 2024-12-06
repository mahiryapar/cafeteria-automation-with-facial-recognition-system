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
if (!isset($_SESSION['user_id'])) {
    die("Kullanıcı giriş yapmamış.");
}
$user_id = $_SESSION['user_id'];
if (!isset($_POST['yemekhane_id']) || empty(trim($_POST['yemekhane_id']))) {
    die("Geçersiz yemekhane ID.");
}
$yemekhane_id = intval(trim($_POST['yemekhane_id']));


$sql_insert = "INSERT INTO katilma_istekleri (user__id, yemekhane_id) VALUES (?, ?)";
$params_insert = [$user_id, $yemekhane_id];
$stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

if ($stmt_insert === false) {
    die("İstek eklenirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
}

header("Location: ../pages/yemekhanem.php?message=IstekGonderildi");
exit;
?>