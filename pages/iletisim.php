<?php 
session_start();
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
if(isset($_SESSION['suanki_mesaj'])){
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
where users.id = ?";
$params_1 = [$_SESSION['user_id']];
$stmt_1 = sqlsrv_query($conn, $sql_1,$params_1);
if ($stmt_1 === false) {
    die(print_r(sqlsrv_errors(), true));
}
if(!isset($_SESSION['suanki_mesaj'])){
    $_SESSION['suanki_mesaj'] = -1; 
}
else if(isset($_SESSION['suanki_mesaj'])){
    $sql_2 = "select mesajlar.id,konu,mesaj,okundu,mesaj_saati,
              (select name from users where users.id=mesajlar.kimden) as name,
              (select surname from users where users.id=mesajlar.kimden) as surname
              from users
              inner join mesajlar
              on users.id = mesajlar.kime
              where users.id = ? and mesajlar.id = ?";
$params_2 = [$_SESSION['user_id'],$_SESSION['suanki_mesaj']];
$stmt_2 = sqlsrv_query($conn, $sql_2,$params_2);
$row_2 = sqlsrv_fetch_array($stmt_2,SQLSRV_FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim</title>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/iletisim.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="#">Öğün Satın Al</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="bakiye_yukle.php">Bakiye Yükle</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">
            <div id="sonuc"></div>
        <div id=iletisim_sayfa>
            <div id="mesaj_yaz_div">
                <span>Adminlerinize Mesaj Yazın</span>
                <hr><br><br>
                <form action="../backend/iletisim_backend.php" method="post" id="mesaj_gonder">
                <label for="kategori">Admin Seçin:</label>
                <select name="kategori" id="kategori">
                    <option value="">Seçiniz</option>
                    <?php

                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value='".$row['id']."'>". $row['name'] ." ". $row['surname']."--" .$row['mail']. "</option>";
                    }
                    ?>
                </select><br><br>
                <input type="text" id="konu" name="konu" placeholder="Konu"><br><br>
                <textarea id="mesaj" name="mesaj" placeholder="Mesajınızı buraya yazın" rows="5" cols="50"></textarea>
                <button type="submit">Gönder</button>
                </form>
            </div>
            <div id='mesaj_detay'>
                <div id="mesaj_gozukuyor">
                    <?php 
                            echo "<span id='mh_kimden'>Yazan: ".$row_2['name']." ".$row_2['surname']."</span><br>
                                  <span id='mh_konu'>Konu: ".$row_2['konu']."</span><br>
                                  <span id='mh_mesaj'>Mesaj: ".$row_2['mesaj']."</span><br>";

                    ?>
                    <?php
                    if (isset($row_2['mesaj_saati']) && $row_2['mesaj_saati'] instanceof DateTime) {
                        $mesajSaati = $row_2['mesaj_saati']->format('Y-m-d H:i:s'); // İstediğiniz format
                        echo "<span id='mh_saati'>Mesaj Zamanı: " . htmlspecialchars($mesajSaati) . "</span><br>";
                    }
                    ?>
                </div>
                <div id="mesajlar_hakkinda_gozukuyor">
                    <?php
                    
                    
                    ?>
                </div>
            </div> 
            <div id=gelen_mesajlar>
            <label>
                 <input type="radio" name="mesaj_tipi" value="gelen" checked> Gelen Mesajlar
            </label>
            <label>
                <input type="radio" name="mesaj_tipi" value="giden"> Giden Mesajlar
            </label>
                <ul id="mesajlar_ul">
                    <?php
                        while ($row_1 = sqlsrv_fetch_array($stmt_1, SQLSRV_FETCH_ASSOC)) {
                            if($row_1['okundu'] == 'Hayır'){
                                echo "<li id='gelen_mesaj_li'><a href='../backend/mesaj_goster.php?mesaj_id=".$row_1['id']."'><span>". $row_1['name'] ." ". $row_1['surname']."--" .$row_1['konu']."-- Mesaj Okunmadı</span><br>
                                         <span> ".$row_1['mesaj']."</span></a></li><br><hr>";
                            }
                            else{
                                echo "<li id='gelen_mesaj_li'><a href='../backend/mesaj_goster.php?mesaj_id=".$row_1['id']."'><span>". $row_1['name'] ." ". $row_1['surname']."--" .$row_1['konu']."-- Mesaj Okundu</span><br>
                                         <span> ".$row_1['mesaj']."</span></a></li><br><hr>";
                            }
                            
                        }
                    ?>
                </ul>
            </div>
        </div>

        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", ()=>{
            if(<?php echo $_SESSION['suanki_mesaj']?> == -1){
                document.getElementById('mesajlar_hakkinda_gozukuyor').style.display = 'block';    
            }
            else{
                document.getElementById('mesaj_gozukuyor').style.display = 'block';
            }

        });
        document.getElementById("mesaj_gonder").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("../backend/iletisim_backend.php", {
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>