<?php 
session_start();
$yemekhane_id = $_SESSION['yemekhane_id'];
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
    $sql = "insert into yorumlar (yorum,sahibi_nickname,sahibi,begeni_sayisi,menu_id) values
           (?,?,?,0,?)";
    if($_POST['form_id'] == 'kahvalti_yorum_yaz'){
        $menu_id = $_SESSION['kahvalti_menu_id'];
    }
    else if($_POST['form_id'] == 'ogle_yorum_yaz'){
        $menu_id = $_SESSION['ogle_yemegi_menu_id'];
    }
    else{
        $menu_id = $_SESSION['aksam_yemegi_menu_id'];
    }
    $yorum = $_POST['yazilan_yorum'];
    $params = [$yorum,$_SESSION['nickname'],$_SESSION['user_id'],$menu_id];
    $stmt = sqlsrv_query($conn, $sql,$params);
    if ($stmt === false) {
         die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo "
                        <div id='cikis' class='alert alert-success'>
                            <strong>Başarılı!</strong> Yorumunuz iletildi!
                        </div>
                        <script>
                            document.getElementById('sonuc').style.display = 'block';
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        </script>";
}





?>