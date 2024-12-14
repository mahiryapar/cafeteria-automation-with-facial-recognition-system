<?php
session_start();
date_default_timezone_set('Europe/Istanbul');
$yemekhane_id = $_SESSION['yemekhane_id'];
$configPath = '../config/database_infos.json';
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
    "CharacterSet" => "UTF-8"        
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedMeals = [];

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'breakfast-') === 0) {
            $selectedMeals[] = ['meal' => 'Kahvaltı', 'date' => substr($key, 10)];
        } else if (strpos($key, 'lunch-') === 0) {
            $selectedMeals[] = ['meal' => 'Öğle Yemeği', 'date' => substr($key, 6)];
        } else if (strpos($key, 'dinner-') === 0) {
            $selectedMeals[] = ['meal' => 'Akşam Yemeği', 'date' => substr($key, 7)];
        }
    }

    $success = false; 

    foreach ($selectedMeals as $meal) {
        $mealName = $meal['meal'];
        $mealDate = $meal['date'];
        
        $sql = "SELECT menu.id,ogun_fiyati FROM menu
        inner join yemekhaneler
        on menu.yemekhane_id = yemekhaneler.id
        inner join yemekhane_ogunleri
        on yemekhaneler.id = yemekhane_ogunleri.yemekhane_id
         WHERE yemekhane_ogunleri.ogun = ? AND menu.kategori = ? and menu_tarihi = ? and yemekhaneler.id = ?";
        $params = [$mealName,$mealName, $mealDate,$_SESSION['yemekhane_id']];
        $stmt = sqlsrv_prepare($conn, $sql, $params);

        if (sqlsrv_execute($stmt)) {
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            if ($result) {
                $menu_id = $result['id'];
                $bakiye = $_SESSION['bakiye'];
                if($result['ogun_fiyati']>$bakiye){
                    echo "
                        <div id='cikis' class='alert alert-danger'>
                            <strong>Hata!</strong> Seçtiğiniz tüm menüler alınamadı. Bakiyeniz yetersiz!
                        </div>
                        <script>
                            document.getElementById('sonuc').style.display = 'block';
                            setTimeout(function() {
                                window.location.href = 'ogun_satin_al.php';
                            }, 2000);
                        </script>";
                        die();
                }
                else{
                    $bakiye = $bakiye - $result['ogun_fiyati'];
                    $_SESSION['bakiye'] = $bakiye;
                    $update_sql = "update users set moneyy = ? where users.id = ?";
                    $params_update = [$bakiye,$_SESSION['user_id']];
                    $update_stmt = sqlsrv_prepare($conn, $update_sql, $params_update);
                    sqlsrv_execute($update_stmt);
                }
                $user_id = $_SESSION['user_id']; 

                $insert_sql = "INSERT INTO alinan_menuler (menu_id, user__id) VALUES (?, ?)";
                $insert_params = [$menu_id, $user_id];
                $insert_stmt = sqlsrv_prepare($conn, $insert_sql, $insert_params);

                if (sqlsrv_execute($insert_stmt)) {
                    $success = true; 
                } else {
                    echo "
                        <div id='cikis' class='alert alert-danger'>
                            <strong>Hata!</strong> Bir hata oluştu lütfen sonra tekrar deneyiniz. ".print_r(sqlsrv_errors(), true)."
                        </div>";
                }
            } else {
                echo "
                    <div id='cikis' class='alert alert-danger'>
                        <strong>Hata!</strong> Menü bulunamadı.
                    </div>";
            }
        } else {
            echo "
                <div id='cikis' class='alert alert-danger'>
                    <strong>Hata!</strong> Bir hata oluştu lütfen sonra tekrar deneyiniz. ".print_r(sqlsrv_errors(), true)."
                </div>";
        }
    }

    if ($success) {
        echo "
            <div id='cikis' class='alert alert-success'>
                <strong>Başarılı!</strong> Seçtiğiniz günler satın alındı.
            </div>
            <script>
                document.getElementById('sonuc').style.display = 'block';
                setTimeout(function() {
                    window.location.href = 'ogun_satin_al.php';
                }, 2000);
            </script>";
    } else {
        echo "
            <div id='cikis' class='alert alert-danger'>
                <strong>Hata!</strong> Öğünler eklenemedi. Lütfen tekrar deneyin.
            </div>";
    }
}

sqlsrv_close($conn);
?>
