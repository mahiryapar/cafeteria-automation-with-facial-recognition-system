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
if (!isset($_GET['id'])) {
    die("Geçersiz istek.");
}

$request_id = intval($_GET['id']); 

$sql = "DELETE FROM katilma_istekleri WHERE id = ?";
$params = [$request_id];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("İstek reddedilirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
}

header("Location: ../pages/yemekhanem.php?message=Reddedildi");
exit();
?>