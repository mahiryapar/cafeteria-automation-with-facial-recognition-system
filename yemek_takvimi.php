<?php 
session_start();
$yemekhane_id = $_SESSION['yemekhane_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekhane</title>
    <link href ="yemek_takvimi_design.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="#">Ana Sayfa</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ortak-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="#">Öğün Satın Al</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="#">Bakiye Yükle</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="#">Öğrenciler</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="logout.php">Çıkış</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">
            <span id="warn">Şu anda bir yemekhaneye kayıtlı değilsiniz.</span>
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
                <div id="calendar" class="calendar"></div>
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
        });
        
    </script>
    <script src="app.js"></script>
    <script src="yemek_takvimi_app.js"></script>
    <link rel="stylesheet" href="design.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>