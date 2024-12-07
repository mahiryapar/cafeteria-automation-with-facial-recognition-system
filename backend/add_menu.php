<?php
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
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}


if ($_SESSION['role'] !== 'admin') {
    die("Bu işlem için yetkiniz yok.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_tarihi = trim($_POST['tarih']);
    $kategori = trim($_POST['ogun']);
    $menu_fiyati = floatval(trim($_POST['fiyat']));
    $yemekhane_id = $_SESSION['yemekhane_id']; 
    $yemekler = [
        'ana_yemek' => $_POST['ana_yemek'],
        'kahvaltilik_1' => $_POST['kahvaltilik_1'] ?? null,
        'kahvaltilik_2' => $_POST['kahvaltilik_2'] ?? null,
        'kahvaltilik_3' => $_POST['kahvaltilik_3'] ?? null,
        'ara_sicak' => $_POST['ara_sicak'] ?? null,
        'corba' => $_POST['corba'] ?? null,
        'tatli' => $_POST['tatli'] ?? null,
        'icecek' => $_POST['icecek']
    ];
    if (empty($menu_tarihi) || empty($kategori) || $menu_fiyati <= 0 || empty($yemekhane_id)) {
        die("Lütfen tüm alanları eksiksiz doldurun.");
    }
    $existing_menu_sql = "SELECT id FROM menu WHERE menu_tarihi = ? and kategori = ? AND yemekhane_id = ?";
    $existing_menu_params = [$menu_tarihi,$kategori, $yemekhane_id];
    $existing_menu_stmt = sqlsrv_query($conn, $existing_menu_sql, $existing_menu_params);
    if ($existing_menu_stmt === false) {
        die("Mevcut menü kontrol edilirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
    }
    if ($existing_menu = sqlsrv_fetch_array($existing_menu_stmt, SQLSRV_FETCH_ASSOC)) {
        $existing_menu_id = $existing_menu['id'];
        $delete_menu_items_sql = "DELETE FROM menudeki_yemekler WHERE menu_id = ?";
        $delete_menu_items_stmt = sqlsrv_query($conn, $delete_menu_items_sql, [$existing_menu_id]);
        if ($delete_menu_items_stmt === false) {
            die("Mevcut menüdeki yemekler silinirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
        }
        $delete_menu_sql = "DELETE FROM menu WHERE id = ?";
        $delete_menu_stmt = sqlsrv_query($conn, $delete_menu_sql, [$existing_menu_id]);
        if ($delete_menu_stmt === false) {
            die("Mevcut menü silinirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
        }
    }
    $menu_sql = "INSERT INTO menu (menu_tarihi, kategori, yemekhane_id, menu_fiyati) OUTPUT INSERTED.id VALUES (?, ?, ?, ?)";
    $menu_params = [$menu_tarihi, $kategori, $yemekhane_id, $menu_fiyati];
    $menu_stmt = sqlsrv_query($conn, $menu_sql, $menu_params);

    if ($menu_stmt === false) {
        die("Menü eklenirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
    }

    $menu_id = null;
    if (sqlsrv_fetch($menu_stmt)) {
        $menu_id = sqlsrv_get_field($menu_stmt, 0);
    }

    if (!$menu_id) {
        die("Menü ID alınamadı. Menü oluşturulamadı.");
    }
    foreach ($yemekler as $kategori => $yemek_adi) {
        if ($yemek_adi) {
            $yemek_id_sorgu = "SELECT id FROM yemekler WHERE yemek_ismi = ?";
            $yemek_id_stmt = sqlsrv_query($conn, $yemek_id_sorgu, [$yemek_adi]);
            if ($yemek_id_stmt === false) {
                die("$kategori kategorisindeki yemek ID'si alınırken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
            }
            $yemek_id = null;
            if ($row = sqlsrv_fetch_array($yemek_id_stmt, SQLSRV_FETCH_ASSOC)) {
                $yemek_id = $row['id'];
            }
            if (!$yemek_id) {
                die("$kategori kategorisindeki yemek için ID bulunamadı: $yemek_adi");
            }
            $yemek_sql = "INSERT INTO menudeki_yemekler (menu_id, yemek_id) VALUES (?, ?)";
            $yemek_params = [$menu_id, $yemek_id];
            $yemek_stmt = sqlsrv_query($conn, $yemek_sql, $yemek_params);
            if ($yemek_stmt === false) {
                die("$kategori kategorisindeki yemek eklenirken bir hata oluştu: " . print_r(sqlsrv_errors(), true));
            }
        }
    }
    header("Location: ../pages/yemekhanem.php?message=menueklendi");
    exit();
} else {
    die("Geçersiz istek.");
}