<?php
session_start();
if(isset($_SESSION['nickname'])){
    $nickname = $_SESSION['nickname'];
    $role = $_SESSION['role'];
    include "../backend/hesap_bilgileri.php";
    $isim = $_SESSION['isim'];
    $soyisim = $_SESSION['soyisim'];
    $bakiye = $_SESSION['bakiye'];
    $mail = $_SESSION['mail'];
    $tel = $_SESSION['tel'];
    $yemekhane = $_SESSION['yemekhane'];
}
else{
    $_SESSION['role'] = "guest";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hesabım</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="#">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="#">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="#">Öğün Satın Al</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>" alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="bakiye_yukle.php">Bakiye Yükle</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="#">Öğrenciler</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">
            
            <div id="hesap_bilgileri">
                <h3>Hesap Bilgilerim</h3>
                <span id="nickname">Kullanıcı Adı: <?php echo $nickname?></span><br>
                <span id="isim">İsim: <?php echo $isim?></span><br>
                <span id="soyisim">Soyisim: <?php echo $soyisim?></span><br>
                <span id="bakiye">Bakiye: <?php echo $bakiye?></span><br>
                <span id="tel">Telefon Numarası: <?php echo $tel?></span><br>
                <span id="mail">E-Posta: <?php echo $mail?></span><br>
                <span id="yemekhane">Yemekhane: <?php echo $yemekhane?></span><br>
                <span id="rol">Rol: <?php echo $role?></span><br>
                <span></span><br>
                <span></span><br>
            </div>
        
        
            <div id="pp_ayar">
            <form action="../backend/upload.php" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="file" class="form-control" name="profile_photo" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                     <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Yükle</button>
                </div>
            </form>
            <form action="../backend/remove_pp.php" method="POST" enctype="multipart/form-data">
                <button type="submit">Profil Fotoğrafını Kaldır</button>
            </form>
            </div>
        </div>
    </div>
    <script>
        window.onload = () => {
            if(<?php echo json_encode($message);?> != null){
                alert(<?php echo json_encode($message);?>);
        }
        };
    </script>
    <script src="../js/app.js"></script>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/hesabim_design.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
