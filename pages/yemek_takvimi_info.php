<?php 
session_start();
include '../backend/fetch_yemek_foto.php';
$yemekhane_id = $_SESSION['yemekhane_id'];
if(isset($_GET['date'])){
    $date = $_GET['date'];
}
$kahv = 0;
$ogle = 0;
$aksam = 0;
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
function yorumlariGetir($menu_id, $conn) {
    $query = "SELECT * FROM yorumlar WHERE menu_id = ?";
    $params = [$menu_id];
    $stmt = sqlsrv_query($conn, $query, $params);

    $yorumlar = [];
    print_r($yorumlar[0]);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $yorumlar[] = $row;
    }
    return $yorumlar;
}

include "../backend/yemek_takvimi_info_backend.php";
$yemek_isimleri = [];
if(isset($_SESSION['kahvalti'])){
    $kahv = 1;
    $kahvalti_ana_yemek = $_SESSION['kahvalti_ana_yemek'];  
    $kahvalti_ana_yemek_aciklama = $_SESSION['kahvalti_ana_yemek_aciklama'];
    $kahvalti_ara_sicak  = $_SESSION['kahvalti_ara_sicak'];
    $kahvalti_ara_sicak_aciklama  = $_SESSION['kahvalti_ara_sicak_aciklama'];
    $kahvalti_corba  = $_SESSION['kahvalti_corba'];
    $kahvalti_corba_aciklama  = $_SESSION['kahvalti_corba_aciklama'];
    $kahvalti_tatli  = $_SESSION['kahvalti_tatli'];
    $kahvalti_tatli_aciklama  = $_SESSION['kahvalti_tatli_aciklama'];
    $kahvalti_icecek  = $_SESSION['kahvalti_icecek'];
    $kahvalti_icecek_aciklama  = $_SESSION['kahvalti_icecek_aciklama'];  
    unset($_SESSION['kahvalti']);
    $kahvalti_yemekleri = [$kahvalti_ana_yemek,$kahvalti_ara_sicak,$kahvalti_corba,$kahvalti_icecek,$kahvalti_tatli];
    $yemek_isimleri = array_merge($kahvalti_yemekleri,$yemek_isimleri);
}
if(isset($_SESSION['ogle_yemegi'])){
    $ogle = 1;
    $ogle_yemegi_ana_yemek = $_SESSION['ogle_yemegi_ana_yemek'];    
    $ogle_yemegi_ana_yemek_aciklama = $_SESSION['ogle_yemegi_ana_yemek_aciklama'];
    $ogle_yemegi_ara_sicak  = $_SESSION['ogle_yemegi_ara_sicak'];
    $ogle_yemegi_ara_sicak_aciklama  = $_SESSION['ogle_yemegi_ara_sicak_aciklama'];
    $ogle_yemegi_corba  = $_SESSION['ogle_yemegi_corba'];
    $ogle_yemegi_corba_aciklama  = $_SESSION['ogle_yemegi_corba_aciklama'];
    $ogle_yemegi_tatli  = $_SESSION['ogle_yemegi_tatli'];
    $ogle_yemegi_tatli_aciklama  = $_SESSION['ogle_yemegi_tatli_aciklama'];
    $ogle_yemegi_icecek  = $_SESSION['ogle_yemegi_icecek'];
    $ogle_yemegi_icecek_aciklama  = $_SESSION['ogle_yemegi_icecek_aciklama'];
    $ogle_yemekleri = [$ogle_yemegi_ana_yemek,$ogle_yemegi_ara_sicak,$ogle_yemegi_corba,$ogle_yemegi_icecek,$ogle_yemegi_tatli];
    unset($_SESSION['ogle_yemegi']);
    $yemek_isimleri = array_merge($ogle_yemekleri,$yemek_isimleri);
}
if(isset($_SESSION['aksam_yemegi'])){
    $aksam=1;
    $aksam_yemegi_ana_yemek = $_SESSION['aksam_yemegi_ana_yemek'];    
    $aksam_yemegi_ana_yemek_aciklama = $_SESSION['aksam_yemegi_ana_yemek_aciklama'];
    $aksam_yemegi_ara_sicak  = $_SESSION['aksam_yemegi_ara_sicak'];
    $aksam_yemegi_ara_sicak_aciklama  = $_SESSION['aksam_yemegi_ara_sicak_aciklama'];
    $aksam_yemegi_corba  = $_SESSION['aksam_yemegi_corba'];
    $aksam_yemegi_corba_aciklama  = $_SESSION['aksam_yemegi_corba_aciklama'];
    $aksam_yemegi_tatli  = $_SESSION['aksam_yemegi_tatli'];
    $aksam_yemegi_tatli_aciklama  = $_SESSION['aksam_yemegi_tatli_aciklama'];
    $aksam_yemegi_icecek  = $_SESSION['aksam_yemegi_icecek'];
    $aksam_yemegi_icecek_aciklama  = $_SESSION['aksam_yemegi_icecek_aciklama'];
    unset($_SESSION['aksam_yemegi']);
    $aksam_yemekleri = [$aksam_yemegi_ana_yemek,$aksam_yemegi_ara_sicak,$aksam_yemegi_corba,$aksam_yemegi_icecek,$aksam_yemegi_tatli];
    $yemek_isimleri = array_merge($aksam_yemekleri,$yemek_isimleri);
}
$resimler = tumYemekFotolariniGetir($yemek_isimleri);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Takvimi</title>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/yemek_takvimi_info_design.css">
    <link href ="../css/yemek_takvimi_design.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ortak-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="ogun_satin_al.php">Öğün Satın Al</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="bakiye_yukle.php">Bakiye Yükle</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="yemekhanem.php">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="yemekhanem.php">Yemekhanem</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="uyeler.php">Üyeler</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="iletisim.php">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">
            <div id="sonuc"></div>
            <span id="warn">Şu anda bir yemekhaneye kayıtlı değilsiniz.</span>
            <span>Tarih: <?php echo $date?></span>
            <div id="yemekler">
            <div id="kahvalti">
                <span>Kahvaltı:<br> </span>
                <span>
                    Ana yemek: <?php echo !empty($kahvalti_ana_yemek) ? $kahvalti_ana_yemek : "Yok"; ?><br> 
                    Açıklama: <?php echo !empty($kahvalti_ana_yemek_aciklama) ? $kahvalti_ana_yemek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_ana_yemek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Kahvaltılık 1: <?php echo !empty($kahvalti_ara_sicak) ? $kahvalti_ara_sicak : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_ara_sicak_aciklama) ? $kahvalti_ara_sicak_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_ara_sicak] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Kahvaltılık 2: <?php echo !empty($kahvalti_corba) ? $kahvalti_corba : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_corba_aciklama) ? $kahvalti_corba_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_corba] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Kahvaltılık 3: <?php echo !empty($kahvalti_tatli) ? $kahvalti_tatli : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_tatli_aciklama) ? $kahvalti_tatli_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_tatli] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    içecek: <?php echo !empty($kahvalti_icecek) ? $kahvalti_icecek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_icecek_aciklama) ? $kahvalti_icecek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_icecek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <div class="yorum_yaz">
                    <form id="kahvalti_yorum_yaz" action="../backend/yorum_ekle.php" method="post">
                    <input type="hidden" name="form_id" value="kahvalti_yorum_yaz">
                    <textarea id="yorum_input" name="yazilan_yorum" placeholder="Yorumunuzu yazın.." rows="5" cols="50"></textarea>
                        <button type="submit">Yorumu gönder</button>
                    </form>
                </div>
                <div class="yorumlar">
                    <h4>Yorumlar:</h4>
                    <ul class="yorumlar_ul"></ul>
                    <?php 
                    $kahvalti_yorumlar = yorumlariGetir($_SESSION['kahvalti_menu_id'], $conn); 
                    if (!empty($kahvalti_yorumlar)) {
                        foreach ($kahvalti_yorumlar as $yorum) {
                            echo "<li class='yorumlar_li'><b>{$yorum['sahibi_nickname']}:</b> {$yorum['yorum']}</p>";
                        }
                    } else {
                        echo "<p>Henüz yorum yok.</p>";
                    }
                    ?>
                </div>
            </div>
            <div id="ogle">
                <span>Öğle Yemeği: <br> </span>
                <span>
                    Ana yemek: <?php echo !empty($ogle_yemegi_ana_yemek) ? $ogle_yemegi_ana_yemek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_ana_yemek_aciklama) ? $ogle_yemegi_ana_yemek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_ana_yemek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Ara sıcak: <?php echo !empty($ogle_yemegi_ara_sicak) ? $ogle_yemegi_ara_sicak : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_ara_sicak_aciklama) ? $ogle_yemegi_ara_sicak_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_ara_sicak] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Çorba: <?php echo !empty($ogle_yemegi_corba) ? $ogle_yemegi_corba : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_corba_aciklama) ? $ogle_yemegi_corba_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_corba] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Tatlı: <?php echo !empty($ogle_yemegi_tatli) ? $ogle_yemegi_tatli : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_tatli_aciklama) ? $ogle_yemegi_tatli_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_tatli] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    içecek: <?php echo !empty($ogle_yemegi_icecek) ? $ogle_yemegi_icecek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_icecek_aciklama) ? $ogle_yemegi_icecek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_icecek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <div class="yorum_yaz">
                    <form id="ogle_yorum_yaz" action="../backend/yorum_ekle.php" method="post">
                    <input type="hidden" name="form_id" value="ogle_yorum_yaz">
                    <textarea id="yorum_input" name="yazilan_yorum" placeholder="Yorumunuzu yazın.." rows="5" cols="50"></textarea>
                        <button type="submit">Yorumu gönder</button>
                    </form>
                </div>
                <div class="yorumlar">
                    <h4>Yorumlar:</h4>
                    <ul class="yorumlar_ul"></ul>
                    <?php 
                    $ogle_yorumlar = yorumlariGetir($_SESSION['ogle_yemegi_menu_id'], $conn); 
                    if (!empty($ogle_yorumlar)) {
                        foreach ($ogle_yorumlar as $yorum) {
                            echo "<li class='yorumlar_li'><b>{$yorum['sahibi_nickname']}:</b> {$yorum['yorum']}</li>";
                        }
                    } else {
                        echo "<p>Henüz yorum yok.</p>";
                    }
                    ?>
                </div>
            </div>
            <div id="aksam">
                <span>Akşam Yemeği: <br> </span>
                <span>
                    Ana yemek: <?php echo !empty($aksam_yemegi_ana_yemek) ? $aksam_yemegi_ana_yemek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_ana_yemek_aciklama) ? $aksam_yemegi_ana_yemek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_ana_yemek]?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Ara sıcak: <?php echo !empty($aksam_yemegi_ara_sicak) ? $aksam_yemegi_ara_sicak : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_ara_sicak_aciklama) ? $aksam_yemegi_ara_sicak_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_ara_sicak] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Çorba: <?php echo !empty($aksam_yemegi_corba) ? $aksam_yemegi_corba : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_corba_aciklama) ? $aksam_yemegi_corba_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_corba] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Tatlı: <?php echo !empty($aksam_yemegi_tatli) ? $aksam_yemegi_tatli : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_tatli_aciklama) ? $aksam_yemegi_tatli_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_tatli] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    içecek: <?php echo !empty($aksam_yemegi_icecek) ? $aksam_yemegi_icecek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_icecek_aciklama) ? $aksam_yemegi_icecek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_icecek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <div class="yorum_yaz">
                    <form id="aksam_yorum_yaz" action="../backend/yorum_ekle.php" method="post">
                    <input type="hidden" name="form_id" value="aksam_yorum_yaz">    
                    <textarea id="yorum_input" name="yazilan_yorum" placeholder="Yorumunuzu yazın.." rows="5" cols="50"></textarea>
                        <button type="submit">Yorumu gönder</button>
                    </form>
                </div>
                <div class="yorumlar">
                    <h4>Yorumlar:</h4>
                    <ul class="yorumlar_ul"></ul>
                    <?php 
                    $aksam_yorumlar = yorumlariGetir($_SESSION['aksam_yemegi_menu_id'], $conn); 
                    if (!empty($aksam_yorumlar)) {
                        foreach ($aksam_yorumlar as $yorum) {
                            echo "<li class='yorumlar_li'><b>{$yorum['sahibi_nickname']}:</b> {$yorum['yorum']}</li>";
                        }
                    } else {
                        echo "<p>Henüz yorum yok.</p>";
                    }
                    ?>
                </div>            
            </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("kahvalti_yorum_yaz").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("../backend/yorum_ekle.php", {
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
        document.getElementById("ogle_yorum_yaz").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("../backend/yorum_ekle.php", {
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
        document.getElementById("aksam_yorum_yaz").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("../backend/yorum_ekle.php", {
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
        document.addEventListener("DOMContentLoaded", ()=>{
            if(<?php echo $yemekhane_id?> == 1){
                document.getElementById('warn').style.display = 'block';
            }
            if(<?php echo $kahv?> == 1){
                document.getElementById('kahvalti').style.display = 'block';
            }
            if(<?php echo $ogle?> == 1){
                document.getElementById('ogle').style.display = 'block';
            }
            if(<?php echo $aksam?> == 1){
                document.getElementById('aksam').style.display = 'block';
            }
        });
    </script>
    <script src="../js/app.js"></script>
    <script src="../js/yemek_takvimi_info.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>