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

if( $conn ) {
    $connection_alert = "Connection established.<br />";
    $sql = "select menu.kategori 
                from yemekhaneler
                inner join menu
                on yemekhaneler.id = menu.yemekhane_id
                where yemekhaneler.id = ?
                group by menu.kategori";
    $params = [$_SESSION['yemekhane_id']];
    $stmt_kategori = sqlsrv_query($conn, $sql,$params);
    if ($stmt_kategori === false) {
         die(print_r(sqlsrv_errors(), true));
    }
    while($row = sqlsrv_fetch_array($stmt_kategori, SQLSRV_FETCH_ASSOC)){
        if($row['kategori'] == 'Kahvaltı'){
            $sql = "SELECT 
                        MAX(CASE WHEN yemekler.kategori = 'Ana yemek' THEN yemekler.yemek_ismi END) AS ana_yemek,
                        MAX(CASE WHEN yemekler.kategori = 'Ana yemek' THEN yemekler.aciklama END) AS ana_yemek_aciklama,
                        STRING_AGG(CASE WHEN yemekler.kategori = 'Ara Sıcak' THEN yemekler.yemek_ismi END, ', ') AS ara_sicaklar,
                        STRING_AGG(CASE WHEN yemekler.kategori = 'Ara Sıcak' THEN yemekler.aciklama END, ', ') AS ara_sicaklar_aciklama,
                        MAX(CASE WHEN yemekler.kategori = 'İçecek' THEN yemekler.yemek_ismi END) AS icecek,
                        MAX(CASE WHEN yemekler.kategori = 'İçecek' THEN yemekler.aciklama END) AS icecek_aciklama,
                        menudeki_yemekler.menu_id
                        FROM 
                            users
                        INNER JOIN 
                            yemekhaneler ON users.yemekhane_id = yemekhaneler.id
                        INNER JOIN 
                            menu ON menu.yemekhane_id = yemekhaneler.id
                        INNER JOIN 
                            menudeki_yemekler ON menu.id = menudeki_yemekler.menu_id
                        INNER JOIN 
                            yemekler ON yemekler.id = menudeki_yemekler.yemek_id
                        WHERE 
                            users.nickname = ?
                            AND menu.menu_tarihi = ?
                            AND menu.kategori = ?
                            AND yemekhaneler.id = ?
                        group by menudeki_yemekler.menu_id";
                $params = [$_SESSION['nickname'],$date,$row['kategori'],$_SESSION['yemekhane_id']];
                $stmt_yemek = sqlsrv_query($conn, $sql,$params);
                if ($stmt_yemek === false) {
                     die(print_r(sqlsrv_errors(), true));
                }
                $row_1 = sqlsrv_fetch_array($stmt_yemek, SQLSRV_FETCH_ASSOC);
                $ara_sicaklar = explode(', ', $row_1['ara_sicaklar']);
                $ara_sicaklar_aciklama = explode(', ', $row_1['ara_sicaklar_aciklama']);
                $prefix = "kahvalti";
                $_SESSION[$prefix] = "setted";
                $_SESSION[$prefix."_menu_id"] = $row_1['menu_id'];
                $_SESSION[$prefix."_ana_yemek"] = $row_1['ana_yemek'];
                $_SESSION[$prefix."_ana_yemek_aciklama"] = $row_1['ana_yemek_aciklama'];
                $_SESSION[$prefix."_ara_sicak_aciklama"] = $ara_sicaklar_aciklama[0];
                $_SESSION[$prefix."_ara_sicak"] = $ara_sicaklar[0];
                $_SESSION[$prefix."_icecek"] = $row_1['icecek'];
                $_SESSION[$prefix."_icecek_aciklama"] = $row_1['icecek_aciklama'];
                $_SESSION[$prefix."_tatli"] = $ara_sicaklar[1];
                $_SESSION[$prefix."_tatli_aciklama"] = $ara_sicaklar_aciklama[1];
                $_SESSION[$prefix."_corba"] = $ara_sicaklar[2];
                $_SESSION[$prefix."_corba_aciklama"] = $ara_sicaklar_aciklama[2];
                }
        elseif($row['kategori'] == 'Öğle Yemeği'){
            $sql = "SELECT 
                MAX(CASE WHEN yemekler.kategori = 'Ana yemek' THEN yemekler.yemek_ismi END) AS ana_yemek,
	            MAX(CASE WHEN yemekler.kategori = 'Ana yemek' THEN yemekler.aciklama END) AS ana_yemek_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'Ara Sıcak' THEN yemekler.yemek_ismi END) AS ara_sicak,
	            MAX(CASE WHEN yemekler.kategori = 'Ara Sıcak' THEN yemekler.aciklama END) AS ara_sicak_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'İçecek' THEN yemekler.yemek_ismi END) AS icecek,
	            MAX(CASE WHEN yemekler.kategori = 'İçecek' THEN yemekler.aciklama END) AS icecek_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'Tatlı' THEN yemekler.yemek_ismi END) AS tatli,
	            MAX(CASE WHEN yemekler.kategori = 'Tatlı' THEN yemekler.aciklama END) AS tatli_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'Çorba' THEN yemekler.yemek_ismi END) AS corba,
	            MAX(CASE WHEN yemekler.kategori = 'Çorba' THEN yemekler.aciklama END) AS corba_aciklama,
                menudeki_yemekler.menu_id
            FROM users
                INNER JOIN yemekhaneler ON users.yemekhane_id = yemekhaneler.id
                INNER JOIN menu ON menu.yemekhane_id = yemekhaneler.id
                INNER JOIN menudeki_yemekler ON menu.id = menudeki_yemekler.menu_id
                INNER JOIN yemekler ON yemekler.id = menudeki_yemekler.yemek_id
            WHERE 
                users.nickname = ?
                AND menu.menu_tarihi = ? 
                AND menu.kategori = ?
                AND yemekhaneler.id = ?
            group by menudeki_yemekler.menu_id";
            $params = [$_SESSION['nickname'],$date,$row['kategori'],$_SESSION['yemekhane_id']];
            $stmt_yemek = sqlsrv_query($conn, $sql,$params);
            if ($stmt_yemek === false) {
                 die(print_r(sqlsrv_errors(), true));
            }
            $row_1 = sqlsrv_fetch_array($stmt_yemek, SQLSRV_FETCH_ASSOC);
            $prefix= "ogle_yemegi";
            $_SESSION[$prefix] = "setted";
            $_SESSION[$prefix."_menu_id"] = $row_1['menu_id'];
            $_SESSION[$prefix."_ana_yemek"] = $row_1['ana_yemek'];
            $_SESSION[$prefix."_ana_yemek_aciklama"] = $row_1['ana_yemek_aciklama'];
            $_SESSION[$prefix."_ara_sicak_aciklama"] = $row_1['ara_sicak_aciklama'];
            $_SESSION[$prefix."_ara_sicak"] = $row_1['ara_sicak'];
            $_SESSION[$prefix."_icecek"] = $row_1['icecek'];
            $_SESSION[$prefix."_icecek_aciklama"] = $row_1['icecek_aciklama'];
            $_SESSION[$prefix."_tatli"] = $row_1['tatli'];
            $_SESSION[$prefix."_tatli_aciklama"] = $row_1['tatli_aciklama'];
            $_SESSION[$prefix."_corba"] = $row_1['corba'];
            $_SESSION[$prefix."_corba_aciklama"] = $row_1['corba_aciklama'];
        }
        else{
            $sql = "SELECT 
                MAX(CASE WHEN yemekler.kategori = 'Ana yemek' THEN yemekler.yemek_ismi END) AS ana_yemek,
	            MAX(CASE WHEN yemekler.kategori = 'Ana yemek' THEN yemekler.aciklama END) AS ana_yemek_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'Ara Sıcak' THEN yemekler.yemek_ismi END) AS ara_sicak,
	            MAX(CASE WHEN yemekler.kategori = 'Ara Sıcak' THEN yemekler.aciklama END) AS ara_sicak_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'İçecek' THEN yemekler.yemek_ismi END) AS icecek,
	            MAX(CASE WHEN yemekler.kategori = 'İçecek' THEN yemekler.aciklama END) AS icecek_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'Tatlı' THEN yemekler.yemek_ismi END) AS tatli,
	            MAX(CASE WHEN yemekler.kategori = 'Tatlı' THEN yemekler.aciklama END) AS tatli_aciklama,
                MAX(CASE WHEN yemekler.kategori = 'Çorba' THEN yemekler.yemek_ismi END) AS corba,
	            MAX(CASE WHEN yemekler.kategori = 'Çorba' THEN yemekler.aciklama END) AS corba_aciklama,
                menudeki_yemekler.menu_id
            FROM users
                INNER JOIN yemekhaneler ON users.yemekhane_id = yemekhaneler.id
                INNER JOIN menu ON menu.yemekhane_id = yemekhaneler.id
                INNER JOIN menudeki_yemekler ON menu.id = menudeki_yemekler.menu_id
                INNER JOIN yemekler ON yemekler.id = menudeki_yemekler.yemek_id
            WHERE 
                users.nickname = ?
                AND menu.menu_tarihi = ? 
                AND menu.kategori = ?
                AND yemekhaneler.id = ?
            group by menudeki_yemekler.menu_id";
            $params = [$_SESSION['nickname'],$date,$row['kategori'],$_SESSION['yemekhane_id']];
            $stmt_yemek = sqlsrv_query($conn, $sql,$params);
            if ($stmt_yemek === false) {
                 die(print_r(sqlsrv_errors(), true));
            }
            $row_1 = sqlsrv_fetch_array($stmt_yemek, SQLSRV_FETCH_ASSOC);
            $prefix= "aksam_yemegi";

            $_SESSION[$prefix] = "setted";
            $_SESSION[$prefix."_menu_id"] = $row_1['menu_id'];
            $_SESSION[$prefix."_ana_yemek"] = $row_1['ana_yemek'];
            $_SESSION[$prefix."_ana_yemek_aciklama"] = $row_1['ana_yemek_aciklama'];
            $_SESSION[$prefix."_ara_sicak_aciklama"] = $row_1['ara_sicak_aciklama'];
            $_SESSION[$prefix."_ara_sicak"] = $row_1['ara_sicak'];
            $_SESSION[$prefix."_icecek"] = $row_1['icecek'];
            $_SESSION[$prefix."_icecek_aciklama"] = $row_1['icecek_aciklama'];
            $_SESSION[$prefix."_tatli"] = $row_1['tatli'];
            $_SESSION[$prefix."_tatli_aciklama"] = $row_1['tatli_aciklama'];
            $_SESSION[$prefix."_corba"] = $row_1['corba'];
            $_SESSION[$prefix."_corba_aciklama"] = $row_1['corba_aciklama'];
        }
        
    }  
}
?>