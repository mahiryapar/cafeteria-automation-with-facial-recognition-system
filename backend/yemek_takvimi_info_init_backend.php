<?php
include '../backend/fetch_yemek_foto.php';
$yemekhane_id = $_SESSION['yemekhane_id'];
if(isset($_GET['date'])){
    $date = $_GET['date'];
}
$kahv = 0;
$ogle = 0;
$aksam = 0;
$configPath ='../config/database_infos.json';
if (!file_exists($configPath)) {
    die('Config dosyası bulunamadı.');
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    die('Config dosyası okunamadı veya geçersiz JSON formatı.');
}
$date = $_GET['date'];
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
function yorumlariGetir($menu_id, $conn) {
    $query = "SELECT * FROM yorumlar WHERE menu_id = ?";
    $params = [$menu_id];
    $stmt = sqlsrv_query($conn, $query, $params);

    $yorumlar = [];
    print_r($yorumlar[0]);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $yorumlar[] = $row;
    }
    return $yorumlar;
}

include "../backend/yemek_takvimi_info_backend.php";
$yemek_isimleri = [];
if(isset($_SESSION['kahvalti'])){
    $kahv = 1;
    $kahvalti_ana_yemek = $_SESSION['kahvalti_ana_yemek'];  
    $kahvalti_ana_yemek_aciklama = $_SESSION['kahvalti_ana_yemek_aciklama'];
    $kahvalti_ara_sicak  = $_SESSION['kahvalti_ara_sicak'];
    $kahvalti_ara_sicak_aciklama  = $_SESSION['kahvalti_ara_sicak_aciklama'];
    $kahvalti_corba  = $_SESSION['kahvalti_corba'];
    $kahvalti_corba_aciklama  = $_SESSION['kahvalti_corba_aciklama'];
    $kahvalti_tatli  = $_SESSION['kahvalti_tatli'];
    $kahvalti_tatli_aciklama  = $_SESSION['kahvalti_tatli_aciklama'];
    $kahvalti_icecek  = $_SESSION['kahvalti_icecek'];
    $kahvalti_icecek_aciklama  = $_SESSION['kahvalti_icecek_aciklama'];  
    unset($_SESSION['kahvalti']);
    $kahvalti_yemekleri = [$kahvalti_ana_yemek,$kahvalti_ara_sicak,$kahvalti_corba,$kahvalti_icecek,$kahvalti_tatli];
    $yemek_isimleri = array_merge($kahvalti_yemekleri,$yemek_isimleri);
}
if(isset($_SESSION['ogle_yemegi'])){
    $ogle = 1;
    $ogle_yemegi_ana_yemek = $_SESSION['ogle_yemegi_ana_yemek'];    
    $ogle_yemegi_ana_yemek_aciklama = $_SESSION['ogle_yemegi_ana_yemek_aciklama'];
    $ogle_yemegi_ara_sicak  = $_SESSION['ogle_yemegi_ara_sicak'];
    $ogle_yemegi_ara_sicak_aciklama  = $_SESSION['ogle_yemegi_ara_sicak_aciklama'];
    $ogle_yemegi_corba  = $_SESSION['ogle_yemegi_corba'];
    $ogle_yemegi_corba_aciklama  = $_SESSION['ogle_yemegi_corba_aciklama'];
    $ogle_yemegi_tatli  = $_SESSION['ogle_yemegi_tatli'];
    $ogle_yemegi_tatli_aciklama  = $_SESSION['ogle_yemegi_tatli_aciklama'];
    $ogle_yemegi_icecek  = $_SESSION['ogle_yemegi_icecek'];
    $ogle_yemegi_icecek_aciklama  = $_SESSION['ogle_yemegi_icecek_aciklama'];
    $ogle_yemekleri = [$ogle_yemegi_ana_yemek,$ogle_yemegi_ara_sicak,$ogle_yemegi_corba,$ogle_yemegi_icecek,$ogle_yemegi_tatli];
    unset($_SESSION['ogle_yemegi']);
    $yemek_isimleri = array_merge($ogle_yemekleri,$yemek_isimleri);
}
if(isset($_SESSION['aksam_yemegi'])){
    $aksam=1;
    $aksam_yemegi_ana_yemek = $_SESSION['aksam_yemegi_ana_yemek'];    
    $aksam_yemegi_ana_yemek_aciklama = $_SESSION['aksam_yemegi_ana_yemek_aciklama'];
    $aksam_yemegi_ara_sicak  = $_SESSION['aksam_yemegi_ara_sicak'];
    $aksam_yemegi_ara_sicak_aciklama  = $_SESSION['aksam_yemegi_ara_sicak_aciklama'];
    $aksam_yemegi_corba  = $_SESSION['aksam_yemegi_corba'];
    $aksam_yemegi_corba_aciklama  = $_SESSION['aksam_yemegi_corba_aciklama'];
    $aksam_yemegi_tatli  = $_SESSION['aksam_yemegi_tatli'];
    $aksam_yemegi_tatli_aciklama  = $_SESSION['aksam_yemegi_tatli_aciklama'];
    $aksam_yemegi_icecek  = $_SESSION['aksam_yemegi_icecek'];
    $aksam_yemegi_icecek_aciklama  = $_SESSION['aksam_yemegi_icecek_aciklama'];
    unset($_SESSION['aksam_yemegi']);
    $aksam_yemekleri = [$aksam_yemegi_ana_yemek,$aksam_yemegi_ara_sicak,$aksam_yemegi_corba,$aksam_yemegi_icecek,$aksam_yemegi_tatli];
    $yemek_isimleri = array_merge($aksam_yemekleri,$yemek_isimleri);
}
$resimler = tumYemekFotolariniGetir($yemek_isimleri);
?>