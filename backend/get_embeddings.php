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
if($conn){
    $menu_id = $_GET['menu_id'];
    $sql = "SELECT embedding FROM users inner join alinan_menuler on users.id=alinan_menuler.user__id WHERE alinan_menuler.menu_id = ?";
    $params = [$menu_id];
    $stmt = sqlsrv_query($conn, $sql,$params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $embeddings = [];
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
        if($row['embedding']!="Yüz Verisi Yok"){
            $embeddings[] = $row['embedding'];
        } 
    }
    echo json_encode($embeddings);
}


?>
