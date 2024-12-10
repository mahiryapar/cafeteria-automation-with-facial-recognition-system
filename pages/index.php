<?php 
session_start();
date_default_timezone_set('Europe/Istanbul');
$now = date('H:i:s');
$sonrakiOgunBaslangic = null;
$aktifOgun = null;

if(isset($_SESSION['nickname'])){
    $nickname = $_SESSION['nickname'];
    $role = $_SESSION['role'];
    include '../backend/fetch_image.php';
    include '../backend/ana_sayfa_backend.php';
} else {
    $_SESSION['role'] = "guest";
    header("Location: giris_kayit.php");
}

$message = null;
if(isset($_SESSION['flash_message'])){
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script src="../js/face-api.js"></script> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekhane</title>
    <link rel="stylesheet" href="../css/ana_sayfa.css">
    <link rel="stylesheet" href="../css/design.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
        <div id="prfl-foto-mobil"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div>
        <div id="menu-toggle">&#9776;</div>
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="#">Ana Sayfa</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
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
        <div id="cikis" class="alert alert-success">
            <strong>Logout!</strong> Başarıyla Çıkış Yapıldı.
        </div>
        <div class='icine-alan-div'>
        <div id="hosgeldiniz" class="box">
        <h3>Hoş Geldiniz <?php echo $_SESSION['isim']." ".$_SESSION['soyisim']; ?>!</h3>
        </div>
        <?php if($role != 'guest'):?>
        <div id="gunun-menusu" class="box">
            <h3>Bugünün Menü İçeriği</h3><hr>
            <?php
            foreach ($_SESSION['ogunler'] as $ogun) {
                if($ogun['ogun'] == "Kahvaltı"){
                    $prefix = "kahvalti";
                }
                else if($ogun['ogun']=="Öğle Yemeği"){
                    $prefix = "ogle_yemegi";
                }
                else{
                    $prefix = "aksam_yemegi";
                }
                echo "<p><strong>Menü:</strong> " . htmlspecialchars($ogun['ogun']) . "</p>";
                echo "<p><strong>İçerik:</strong> " .$_SESSION[$prefix."_ana_yemek"] .", ".$_SESSION[$prefix."_ara_sicak"].", ".$_SESSION[$prefix."_corba"].", ".$_SESSION[$prefix."_tatli"].", ".$_SESSION[$prefix."_icecek"]. "</p>";
                if (isset($ogun['ogun_saati']) && isset($ogun['ogun_bitis_saati'])) {
                    $ogunsaatiRaw = explode('.', $ogun['ogun_saati'])[0];
                    $ogunbitissaatiRaw = explode('.', $ogun['ogun_bitis_saati'])[0];
                    $ogunsaati = DateTime::createFromFormat('H:i:s', $ogunsaatiRaw);
                    $ogunbitissaati = DateTime::createFromFormat('H:i:s', $ogunbitissaatiRaw);
                    if ($ogunsaati && $ogunbitissaati) {
                        if ($now >= $ogunsaati->format('H:i:s') && $now <= $ogunbitissaati->format('H:i:s')) {
                            $aktifOgun = $ogun;
                        }
                        if ($now < $ogunsaati->format('H:i:s') && (!$sonrakiOgunBaslangic || $ogunsaati->format('H:i:s') < $sonrakiOgunBaslangic)) {
                            $sonrakiOgunBaslangic = $ogunsaati;
                        }
                        echo "<p><strong>Servis Saatleri:</strong> " . $ogunsaati->format('H:i') . " - " . $ogunbitissaati->format('H:i') . "</p><hr>";
                    } else {
                        echo "<p><strong>Servis Saatleri:</strong> Geçersiz zaman formatı</p>";
                        echo "<p>Ham Zaman: " . htmlspecialchars($ogunsaatiRaw) . " - " . htmlspecialchars($ogunbitissaatiRaw) . "</p>";
                    }
                }
            }
            
            ?>
        </div>
        <?php endif;?>
        <?php if($role === 'admin'): ?>
        <div id="yemek-baslat" class="box">
            <h3>Yemek Başlat</h3>
            <?php if ($aktifOgun): ?>
            <p>Şu anki öğün: <strong><?php echo htmlspecialchars($aktifOgun['ogun']); ?></strong></p>
            <button id="startBtn" class="btn btn-primary">Yemeği Başlat</button>
             <?php elseif ($sonrakiOgunBaslangic): ?>
            <p>Bir sonraki öğün, <?php echo $sonrakiOgunBaslangic->format('H:i'); ?>'de başlayacak.</p>
            <?php else: ?>
            <p>Bugün için başka öğün yok.</p>
            <?php endif; ?>
        </div>
        </div>
        <div id="overlay">
            <div id="overlay-content">
                <h3>Kamera Açılıyor...</h3>
                <video id="videoElement" width="640" height="480" autoplay></video>
                <div id="info_div"><p id="yuzAlgilamaDurumu">Yüz algılanırsa, bilgiler burada gösterilecektir.</p></div>
                <button id="kapatButonu" class="btn btn-danger">Kapat</button>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
        </div>
    </div>
    <script src="../js/embedding_karsilastirma.js"></script>
    <script>
        window.onload = () => {
            if(<?php echo json_encode($message);?> != null){
                document.getElementById('cikis').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('cikis').style.display = 'none';
                }, 2000);
            }
        };

        if("<?php echo $aktifOgun["ogun"];?>" == "Kahvaltı"){
            deger_al(<?php echo $_SESSION['kahvalti_menu_id'];?>);
        }
        else if("<?php echo $aktifOgun["ogun"];?>" == "Öğle"){
            deger_al(<?php echo $_SESSION['ogle_yemegi_menu_id'];?>);
        }
        else{
            deger_al(<?php echo $_SESSION['aksam_yemegi_menu_id'];?>);
        }
    </script> 

    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
</body>
</html>





