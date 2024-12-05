<?php
session_start();
$_SESSION['sayfa'] = 'iletisim';
header('Content-Type: text/html; charset=utf-8');
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
if(isset($_SESSION['suanki_mesaj'])&&$_SESSION['gelgit']==0){
    $sql_2 = "update mesajlar set okundu = 'Evet' where mesajlar.id = ?";
    $params_2 = [$_SESSION['suanki_mesaj']];
    $stmt_2 = sqlsrv_query($conn, $sql_2,$params_2);
    $row_2 = sqlsrv_fetch_array($stmt_2,SQLSRV_FETCH_ASSOC);
}
$sql = "SELECT id,name,surname,mail FROM users where rol='admin' and yemekhane_id = ?";
$params = [$_SESSION['yemekhane_id']];
$stmt = sqlsrv_query($conn, $sql,$params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$sql_1 = "select mesajlar.id,konu,mesaj,okundu,
(select name from users where users.id=mesajlar.kimden) as name,
(select surname from users where users.id=mesajlar.kimden) as surname
from users
inner join mesajlar
on users.id = mesajlar.kime
where users.id = ?
order by mesaj_saati desc";
$params_1 = [$_SESSION['user_id']];
$stmt_1 = sqlsrv_query($conn, $sql_1,$params_1);
if ($stmt_1 === false) {
    die(print_r(sqlsrv_errors(), true));
}
$sql_3 = "select mesajlar.id,konu,
(select name from users where users.id=mesajlar.kime) as name,
(select surname from users where users.id=mesajlar.kime) as surname
from users
inner join mesajlar
on users.id = mesajlar.kimden
where users.id = ?
order by mesaj_saati desc";
$params_3 = [$_SESSION['user_id']];
$stmt_3 = sqlsrv_query($conn, $sql_3,$params_3);
if ($stmt_3 === false) {
    die(print_r(sqlsrv_errors(), true));
}

if(!isset($_SESSION['suanki_mesaj']) || $_SESSION['suanki_mesaj'] == -1){
    $_SESSION['suanki_mesaj'] = -1; 
    $sql_5 = "select name, surname, count(mesajlar.id) as okunmamis
              from users
              inner join mesajlar
              on users.id = mesajlar.kime
              where users.id = ? and mesajlar.okundu = 'Hayır'
              group by users.name,users.surname";
    $params_5 = [$_SESSION['user_id']];
    $stmt_5 = sqlsrv_query($conn, $sql_5,$params_5);
    $row_5 = sqlsrv_fetch_array($stmt_5,SQLSRV_FETCH_ASSOC);
    if(sqlsrv_has_rows($stmt_5)){
        $okunmamis_mesaj = 1;
    }
    else{
        $okunmamis_mesaj = 0;
    }
}
else if(isset($_SESSION['suanki_mesaj'])){
    if($_SESSION['gelgit'] == 0){
        $sql_2 = "select mesajlar.id,konu,mesaj,okundu,mesaj_saati,
              (select name from users where users.id=mesajlar.kimden) as name,
              (select surname from users where users.id=mesajlar.kimden) as surname
              from users
              inner join mesajlar
              on users.id = mesajlar.kime
              where users.id = ? and mesajlar.id = ?";
    }
    else{
        $sql_2 = "select mesajlar.id,konu,mesaj,okundu,mesaj_saati,
              (select name from users where users.id=mesajlar.kime) as name,
              (select surname from users where users.id=mesajlar.kime) as surname
              from users
              inner join mesajlar
              on users.id = mesajlar.kimden
              where users.id = ? and mesajlar.id = ?";
    }
    $params_2 = [$_SESSION['user_id'],$_SESSION['suanki_mesaj']];
    $stmt_2 = sqlsrv_query($conn, $sql_2,$params_2);
    $row_2 = sqlsrv_fetch_array($stmt_2,SQLSRV_FETCH_ASSOC);
}



?>