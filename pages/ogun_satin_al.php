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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğün Satın Al</title>
    <link rel="stylesheet" href="../css/design.css">
    <link href ="../css/yemek_takvimi_design.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ortak-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="#">Öğün Satın Al</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="bakiye_yukle.php">Bakiye Yükle</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="yemekhanem.php">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="iletisim.php">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">
            <div id="sonuc"></div>
            <span id="warn">Şu anda bir yemekhaneye kayıtlı değilsiniz.</span>
            <span id="bakiye">Şuanki bakiyeniz: <?php echo $row_bakiye['moneyy'];?><br><br></span>
            <span id="ucretler">  <?php
                                    $ucretler = [];
                                    while ($row_ucret = sqlsrv_fetch_array($stmt_ucret, SQLSRV_FETCH_ASSOC)) {
                                        $kategori = $row_ucret['kategori'];
                                        $fiyat = $row_ucret['menu_fiyati'];
                                        $ucretler[] = "$kategori: $fiyat TL";
                                    }
                                    if (!empty($ucretler)) {
                                        echo implode(" | ", $ucretler);
                                    } else {
                                        echo "Ücretler bulunamadı.";
                                    }
                                    ?></span>
            <div class="container">
                <div class="calendar">
                    <div class="weekdays">Pazartesi</div>
                    <div class="weekdays">Salı</div>
                    <div class="weekdays">Çarşamba</div>
                    <div class="weekdays">Perşembe</div>
                    <div class="weekdays">Cuma</div>
                    <div class="weekdays">Cumartesi</div>
                    <div class="weekdays">Pazar</div>
                </div>
                <form id = "ogun_al"action="../backend/ogunleri_al.php" method="POST">
                    <div id="calendar" class="calendar"></div>
                    <button type="submit">Seçimleri Gönder</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", ()=>{
            if(<?php echo $yemekhane_id?> == 1){
                document.getElementById('warn').style.display = 'block';
            }
            else{
                document.querySelector('.container').style.display = 'block';
            }
            
            var ogunler = <?php echo json_encode($ogunler, JSON_UNESCAPED_UNICODE); ?>;
            var ogunSecenekleri = document.querySelectorAll('.meal-option');
            var alinanMenuler = <?php echo json_encode($alinan_menuler, JSON_UNESCAPED_UNICODE); ?>;
            ogunSecenekleri.forEach(function(option) {
                var input = option.querySelector('input');
                var ogunAdi = input.value;
                var ogunTarihi = input.name.split('-')[1]+"-"+input.name.split('-')[2]+"-"+input.name.split('-')[3];
                var menuBilgisi = alinanMenuler.find(
                        m => m.kategori === ogunAdi && m.tarih === ogunTarihi
                );
                
                if (menuBilgisi) {
                        input.checked = true;
                        input.disabled = true;
                }
                if (!ogunler.includes(ogunAdi)) {
                    option.style.display = 'none';
                } 
        });
        });
        document.getElementById("ogun_al").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("../backend/ogunleri_al.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.text()) 
            .then(data => {
            document.getElementById("sonuc").innerHTML = data;
            const scripts = document.getElementById("sonuc").getElementsByTagName("script");
            for (let script of scripts) {
                eval(script.textContent); 
            }
            })
            .catch(error => {
                console.error("Hata:", error);
            });
            });
        
    </script>
    <script src="../js/app.js"></script>
    <script src="../js/ogun_satin_al.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>