<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
$configPath ='../config/database_infos.json';
if (!file_exists($configPath)) {
    die('Config dosyası bulunamadı.');
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    die('Config dosyası okunamadı veya geçersiz JSON formatı.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $mesaj = $_POST['mesaj'] ?? '';
        $konu = $_POST['konu'] ?? '';
        $kime = $_POST['kategori']; 
        if (empty($kime)) {
            echo "
            <div id='cikis' class='alert alert-danger'>
                <strong>Hata!</strong> Lütfen Admin Seçiniz!
            </div>";
            die();
        }
        $sql = "INSERT INTO mesajlar(kimden,kime,mesaj,konu) values 
        (?,?,?,?)";      
        $params = [$_SESSION['user_id'],$kime,$mesaj, $konu];
        $stmt = sqlsrv_query($conn, $sql,$params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        echo "
            <div id='cikis' class='alert alert-success'>
                <strong>Başarılı!</strong> Mesajınız Başarıyla Gönderildi.
            </div>
            <script>
                document.getElementById('sonuc').style.display = 'block';
                setTimeout(function() {
                    window.location.href = 'iletisim.php';
                }, 2000);
            </script>";
        sqlsrv_free_stmt($stmt);
    }
    else{
        $connection_alert = "Bağlantı Hatası.<br />";
        echo $connection_alert;
    }  
} else {
    echo "Geçersiz istek.";
}
?>