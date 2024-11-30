<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
$connection_alert;
$query;
$configPath ='../config/database_infos.json';
if (!file_exists($configPath)) {
    die('Config dosyası bulunamadı.');
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    die('Config dosyası okunamadı veya geçersiz JSON formatı.');
}

$serverName = $config['db_host']; 
$database = $config['db_name'];
$uid = $config['db_user'];
$pass = $config['db_password'];
$connection_info = [
    "Database" =>   $database,
    "Uid" => $uid,
    "PWD" => $pass,
    "CharacterSet"=>"UTF-8"
];
$conn = sqlsrv_connect($serverName,$connection_info);

if( $conn ) {
     $connection_alert = "Connection established.<br />";
     $sql = "SELECT moneyy FROM Users where nickname = ?";
     $params = [$_SESSION['nickname']];
     $stmt = sqlsrv_query($conn, $sql,$params);

    if ($stmt === false) {
         die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo $row['moneyy'];
}
?>