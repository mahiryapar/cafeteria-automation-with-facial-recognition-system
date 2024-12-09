<?php

session_start();
header('Content-Type: application/json; charset=utf-8');

$configPath = '../config/database_infos.json';
if (!file_exists($configPath)) {
    echo json_encode(['error' => 'Config dosyası bulunamadı.']);
    exit;
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    echo json_encode(['error' => 'Config dosyası geçersiz.']);
    exit;
}

$serverName = $config['db_host'];
$database = $config['db_name'];
$uid = $config['db_user'];
$pass = $config['db_password'];

$connection_info = [
    "Database" => $database,
    "Uid" => $uid,
    "PWD" => $pass,
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connection_info);
if (!$conn) {
    echo json_encode(['error' => 'Database bağlantısı başarısız.']);
    exit;
}

$menu_id = $_GET['menu_id'] ?? null; // menu_id kontrolü
if (!$menu_id) {
    echo json_encode(['error' => 'Menu ID eksik.']);
    exit;
}

$sql = "SELECT embedding FROM users INNER JOIN alinan_menuler ON users.id = alinan_menuler.user__id WHERE alinan_menuler.menu_id = ?";
$params = [$menu_id];

$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    error_log(print_r(sqlsrv_errors(), true));
    echo json_encode(['error' => 'Database sorgusu başarısız.']);
    exit;
}

$embeddings = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['embedding'] != "Yüz Verisi Yok") {
        $embeddings[] = ['embedding' => json_decode($row['embedding'])]; // JSON'ı çöz
    }
}

echo json_encode($embeddings);

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
