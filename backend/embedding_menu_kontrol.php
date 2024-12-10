<?php
$configPath = '../config/database_infos.json';
if (!file_exists($configPath)) {
    die(json_encode(['error' => 'Config dosyası bulunamadı.']));
}
$config = json_decode(file_get_contents($configPath), true);

$serverName = $config['db_host'];
$connectionInfo = [
    "Database" => $config['db_name'],
    "Uid" => $config['db_user'],
    "PWD" => $config['db_password'],
    "CharacterSet" => "UTF-8"
];
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(json_encode(['error' => 'Veritabanı bağlantısı başarısız.', 'details' => sqlsrv_errors()]));
}

$menu_id = $_GET['menu_id'];
$user_id = $_GET['user_id'];

$sql = "SELECT yendi_mi FROM alinan_menuler WHERE menu_id = ? AND user__id = ?";
$params = [$menu_id, $user_id];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(['error' => 'Sorgu hatası.', 'details' => sqlsrv_errors()]));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    $bilgilersql = "SELECT * FROM users WHERE id = ?";
    $bilgiparams = [$user_id];
    $bilgistmt = sqlsrv_query($conn, $bilgilersql, $bilgiparams);
    $bilgiler = sqlsrv_fetch_array($bilgistmt, SQLSRV_FETCH_ASSOC);
    if ($row['yendi_mi'] === 'Evet') {
        echo json_encode(['status' => 'yendi', 'message' => "Bu menü zaten yendi.<br> Kullanıcı: " . $bilgiler['name'] . " " . $bilgiler['surname'] . ""]);
    } else {
        // Güncelleme işlemi
        $updateSql = "UPDATE alinan_menuler SET yendi_mi = 'Evet' WHERE menu_id = ? AND user__id = ?";
        $updateStmt = sqlsrv_query($conn, $updateSql, $params);
        if ($updateStmt === false) {
            die(json_encode(['error' => 'Güncelleme başarısız.', 'details' => sqlsrv_errors()]));
        }

        echo json_encode(['status' => 'yeni_yendi', 'message' => "Yemek başarıyla yendi olarak işaretlendi.<br> Kullanıcı: " . $bilgiler['name'] . " " . $bilgiler['surname'] . ""]);
    }
} else {
    echo json_encode(['status' => 'yok', 'message' => 'Bu kullanıcı bu menüyü almamış.']);
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>