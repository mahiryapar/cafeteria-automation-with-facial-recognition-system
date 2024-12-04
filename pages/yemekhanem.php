<?php 
session_start();

if(isset($_SESSION['nickname'])){
    $nickname = $_SESSION['nickname'];
    $role = $_SESSION['role'];
    include '../backend/fetch_image.php';
} else {
    $_SESSION['role'] = "guest";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekhanem</title>
    <link rel="stylesheet" href="../css/design.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="ogun_satin_al.php">Öğün Satın Al</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="bakiye_yukle.php">Bakiye Yükle</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="uyeler.php">Üyeler</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="iletisim.php">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">

        <?php if($role === 'admin'): ?>

        <?php endif; ?>
        </div>
    </div>
    <script>
        window.onload = () => {
            if(<?php echo json_encode($message);?> != null){
                document.getElementById('cikis').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('cikis').style.display = 'none';
                }, 2000);
        }
        };
    </script>
    
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
