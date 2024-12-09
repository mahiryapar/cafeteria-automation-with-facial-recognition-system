<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if (!isset($_SESSION['user_id'])) {
        die('User ID oturumda bulunamadı.');
    }
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $embedding = $_POST['embedding'];
    $embeddingJson = json_encode($embedding);
    $query = "update users set embedding = ? where id = ?";
    $params = [$embeddingJson, $_SESSION['user_id']];

    $stmt = sqlsrv_query($conn, $query, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo "
                <div id='cikis' class='alert alert-success'>
                    <strong>Başarılı!</strong> Kayıt olundu. Giriş yapabilirsiniz.
                </div>
                <script>
                    document.getElementById('sonuc').style.display = 'block';
                    setTimeout(function() {
                        window.location.href = 'giris_kayit.php?giris=1';
                    }, 200000);
                </script>";
    sqlsrv_close($conn);
}
?>