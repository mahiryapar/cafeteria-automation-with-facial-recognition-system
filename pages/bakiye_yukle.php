<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakiye Yükle</title>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/bakiye_yukle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
        <div id="prfl-foto-mobil"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div>
        <div id="menu-toggle">&#9776;</div>
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="ogun_satin_al.php">Öğün Satın Al</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="#">Bakiye Yükle</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="yemekhanem.php">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="iletisim.php">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>

        </nav>
        <div id="icerik">
            <div id="sonuc"></div>
            <div id='bakiye_yuklediv' class='box'>
            <span id="mevcut_bakiye">Şu anki bakiyeniz: <?php include '../backend/bakiye_bilgisi_cek.php'?></span>
            <form id="para_yukle_form" action="../backend/bakiye_yukle_backend.php" method="post"><br>
                <input type="number" id="kart_no" name="kart_no" placeholder="Kart Numarası"><br>
                <input type="month"   id="skt" name="skt" placeholder="Son Kullanma Tarihi" min="2024-12" max="2035-12" ><br>
                <input type="number" id="cvv" name="cvv" placeholder="CVV Numarası"><br>
                <input type="number" id="bakiye" name="bakiye"placeholder="Yüklemek İstediğin Bakiye"><br>
                <button type="submit">Para Yükle</button><br>
            </form>
            </div> 
        </div>
    </div>
    <script src="../js/bakiye_yukle.js"></script>
    <script src="../js/app.js"></script>   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

