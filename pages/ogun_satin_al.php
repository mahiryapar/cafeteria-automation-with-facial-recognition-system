<?php 
session_start();
include '../backend/ogun_satin_al_init_backend.php';
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
    <script src="../js/ogun_satin_al.js"></script>
    <script>
        deger_al(<?php echo $yemekhane_id?>,<?php echo json_encode($ogunler, JSON_UNESCAPED_UNICODE); ?>, <?php echo json_encode($alinan_menuler, JSON_UNESCAPED_UNICODE); ?>);
    </script>
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>