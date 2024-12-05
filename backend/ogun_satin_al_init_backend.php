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
$sql_ucret="select menu.kategori,menu.menu_fiyati
            from menu
            inner join yemekhaneler
            on menu.yemekhane_id = yemekhaneler.id
            where yemekhane_id = ?
            group by menu.kategori,menu_fiyati";
$params_ucret = [$_SESSION['yemekhane_id']];
$stmt_ucret = sqlsrv_query($conn, $sql_ucret, $params_ucret);
$sql_bakiye="select moneyy from users where id = ?";
$params_bakiye = [$_SESSION['user_id']];
$stmt_bakiye = sqlsrv_query($conn, $sql_bakiye, $params_bakiye);
$row_bakiye = sqlsrv_fetch_array($stmt_bakiye, SQLSRV_FETCH_ASSOC);
$_SESSION['bakiye'] = $row_bakiye['moneyy'];
$sql = "
    select ogun 
    from yemekhane_ogunleri
    where yemekhane_id = ?";
$params = [$_SESSION['yemekhane_id']];
$stmt = sqlsrv_query($conn, $sql, $params);
$ogunler = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $ogunler[] = $row['ogun'];
}
$sql_2 = "select menu.kategori,menu.menu_tarihi
from menu
inner join alinan_menuler
on alinan_menuler.menu_id = menu.id
inner join users 
on users.id = alinan_menuler.user__id
where users.id = ?
";
$params_2 = [$_SESSION['user_id']];
$stmt_2 = sqlsrv_query($conn, $sql_2, $params_2);
$alinan_menuler = array();
while ($row_2 = sqlsrv_fetch_array($stmt_2, SQLSRV_FETCH_ASSOC)) {
    $alinan_menuler[] = [
        'tarih' => $row_2['menu_tarihi']->format('Y-m-d'),
        'kategori' => $row_2['kategori']
    ];
}

?>