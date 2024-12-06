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

$request_id = intval(trim($_GET['id']));
$sql = "SELECT user__id, yemekhane_id FROM katilma_istekleri WHERE id = ?";
$params = [$request_id];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || !($istek = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    die("İstek bulunamadı.");
}

$user_id = $istek['user__id'];
$yemekhane_id = $istek['yemekhane_id'];

$sqlUpdate = "UPDATE users SET yemekhane_id = ? WHERE id = ?";
$paramsUpdate = [$yemekhane_id, $user_id];
$stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

if ($stmtUpdate === false) {
    die("Üyelik onaylanırken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
}

$sqlDelete = "DELETE FROM katilma_istekleri WHERE id = ?";
$paramsDelete = [$request_id];
$stmtDelete = sqlsrv_query($conn, $sqlDelete,$paramsDelete);

if ($stmtDelete === false) {
    die("İstek silinirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
}

header("Location: ../pages/yemekhanem.php?message=Onaylandı");
exit();
?>