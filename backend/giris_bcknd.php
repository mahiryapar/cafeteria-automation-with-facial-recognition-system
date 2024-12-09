<?php
header('Content-Type: text/html; charset=utf-8');
$configPath ='../config/database_infos.json';
if (!file_exists($configPath)) {
    die('Config dosyası bulunamadı.');
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    die('Config dosyası okunamadı veya geçersiz JSON formatı.');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        if($_GET['giris'] == 1){
            $kullaniciAdi = $_POST['login_kullanici_adi'] ?? '';
            $sifre = $_POST['login_sifre'] ?? '';
            $sql = "SELECT id, rol, yemekhane_id, name, surname, password_hash FROM Users WHERE nickname = ?";
            $params = [$kullaniciAdi];
            $stmt = sqlsrv_query($conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            if(sqlsrv_has_rows($stmt)){
                session_start();
                $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                if (password_verify($sifre, $row['password_hash'])){   
                    $_SESSION['nickname'] = $kullaniciAdi; 
                    $_SESSION['isim'] = $row['name'];
                    $_SESSION['soyisim'] = $row['surname'];
                    $_SESSION['role'] = $row['rol'];
                    $_SESSION['yemekhane_id'] = $row['yemekhane_id'];
                    $_SESSION['user_id'] = $row['id'];
                    echo "
                    <div id='cikis' class='alert alert-success'>
                        <strong>Başarılı!</strong> Giriş yapıldı. Ana sayfaya yönlendiriliyorsunuz.
                    </div>
                    <script>
                        document.getElementById('sonuc').style.display = 'block';
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 2000);
                    </script>";
                }
                else {
                    echo "<div id='cikis' class='alert alert-danger'>
                        <strong>Hata!</strong> Kullanıcı adı veya şifre hatalı.
                    </div>
                    <script>
                        document.getElementById('sonuc').style.display = 'block';
                        setTimeout(function() {
                            document.getElementById('sonuc').style.display = 'none';
                        }, 2000);
                    </script>";
                }
            }
            else{
                echo "<div id='cikis' class='alert alert-danger'>
                    <strong>Hata!</strong> Kullanıcı adı veya şifre hatalı.
                </div>
                <script>
                    document.getElementById('sonuc').style.display = 'block';
                    setTimeout(function() {
                        document.getElementById('sonuc').style.display = 'none';
                    }, 2000);
                </script>";
            }
            sqlsrv_free_stmt($stmt);
        }   
        else{
            $kullaniciAdi = $_POST['kullanici_adi'] ?? '';
            $sifre = $_POST['sifre'] ?? '';
            $mail = $_POST['mail'] ?? '';
            $number = $_POST['number'] ?? '';
            $isim = $_POST['isim'] ?? '';
            $soyisim = $_POST['soyisim'] ?? '';
            $sql = "SELECT * FROM Users where nickname = ? or phonenum= ? or mail= ?";      
            $params = [$kullaniciAdi,$number,$mail];
            $stmt = sqlsrv_query($conn, $sql,$params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            if(sqlsrv_has_rows($stmt)){
                echo "
                <div id='cikis' class='alert alert-danger'>
                    <strong>Hata!</strong> Bu kullanıcı adı, telefon veya maile sahip biri bulunmaktadır.
                </div>
                <script>
                    document.getElementById('sonuc').style.display = 'block';
                    setTimeout(function() {
                        document.getElementById('sonuc').style.display = 'none';
                    }, 2000);
                </script>";
            }
            else{
                $hashed_password = password_hash($sifre, PASSWORD_DEFAULT);

                // SQL sorgusu
                $sql = "INSERT INTO users (name, surname, mail, phonenum, moneyy, nickname, password_hash, rol, yemekhane_id) 
                        VALUES (?, ?, ?, ?, 0, ?, ?, 'ogrenci', 1); 
                        SELECT SCOPE_IDENTITY() AS last_id;";
                
                $params = [$isim, $soyisim, $mail, $number, $kullaniciAdi, $hashed_password];
                $stmt = sqlsrv_query($conn, $sql, $params);
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                if (sqlsrv_next_result($stmt) && sqlsrv_fetch($stmt)) {
                    $lastId = sqlsrv_get_field($stmt, 0);
                    session_start();
                    $_SESSION['user_id'] = $lastId; // Oturum değişkenine kaydet
                } else {
                    echo "Son ID alınamadı.";
                }
                echo "
                <div id='cikis' class='alert alert-success'>
                    <strong>Başarılı!</strong> Kayıt olundu. ".$_SESSION['user_id']."Yüz ekleme sayfasına yönlendiriliyorsunuz..
                </div>
                <script>
                    document.getElementById('sonuc').style.display = 'block';
                    setTimeout(function() {
                        window.location.href = 'yuz_ekle.php';
                    }, 2000);
                </script>";
            }
            sqlsrv_free_stmt($stmt);

        }
        sqlsrv_close($conn);
    }
    else{
        $connection_alert = "Bağlantı Hatası.<br />";
        echo $connection_alert;
    }  
} else {
    echo "Geçersiz istek.";
}
?>
