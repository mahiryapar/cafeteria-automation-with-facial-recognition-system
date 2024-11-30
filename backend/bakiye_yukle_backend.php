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
    $bakiye = $row['moneyy'];
    $eklenecek_para = $_POST['bakiye'];
    $son_para = $bakiye + $eklenecek_para;
    $sql = "UPDATE users set moneyy = ? where nickname = ?";
    $params = [$son_para,$_SESSION['nickname']];
    $stmt = sqlsrv_query($conn, $sql,$params);
    if ($stmt === false) {
         die(print_r(sqlsrv_errors(), true));
    }
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    echo "
                <div id='cikis' class='alert alert-success'>
                    <strong>Başarılı!</strong> Bakiye başarıyla yüklendi. Ana sayfaya yönlendiriliyorsunuz.
                </div>
                <script>
                    document.getElementById('sonuc').style.display = 'block';
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000);
                </script>";
}else{
    $connection_alert = "Connection could not be established.<br />";
    die( print_r( sqlsrv_errors(), true));
    echo "
                <div id='cikis' class='alert alert-danger'>
                    <strong>Hata!</strong> Bakiye yüklenemedi.
                </div>
                <script>
                    document.getElementById('sonuc').style.display = 'block';
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000);
                </script>";
}
?>