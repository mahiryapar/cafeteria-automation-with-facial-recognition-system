<?php 
session_start();

if(isset($_SESSION['nickname'])){
    $nickname = $_SESSION['nickname'];
    $role = $_SESSION['role'];
    include '../backend/fetch_image.php';
} else {
    $_SESSION['role'] = "guest";
}

$message = null;
if(isset($_SESSION['flash_message'])){
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

// Günün menüsü örneği
$gununMenusu = [
    'menu' => 'Tavuk Çorbası, Izgara Köfte, Pilav, Salata',
    'baslangic_saati' => '12:00',
    'bitis_saati' => '14:00'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekhane</title>
    <link rel="stylesheet" href="../css/design.css">
    <style>
        .hosgeldiniz, .gunun-menusu, .yemek-baslat {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            z-index: 1000;
        }
        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        video {
            width: 300px;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
    <style>
        #cikis{
            display:none;
            margin-left:20vw;
            margin-right:20vw;
            margin-top:3vh;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
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
                <li class="liler" id="admin-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="#">Yemekhanem</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="uyeler.php">Üyeler</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="iletisim.php">İletişim</a></li>
                <li class="liler" id="ogrenci-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">
            <div id="cikis" class="alert alert-success">
                <strong>Logout!</strong> Başarıyla Çıkış Yapıldı.
            </div>
            <div class="hosgeldiniz">
            <h3>Hoş Geldiniz, <?php echo htmlspecialchars($nickname); ?>!</h3>
        </div>

        <div class="gunun-menusu">
            <h3>Günün Menüsü</h3>
            <p><strong>Menü:</strong> <?php echo $gununMenusu['menu']; ?></p>
            <p><strong>Servis Saatleri:</strong> <?php echo $gununMenusu['baslangic_saati']; ?> - <?php echo $gununMenusu['bitis_saati']; ?></p>
        </div>

        <?php if($role === 'admin'): ?>
        <div class="yemek-baslat">
            <h3>Yemek Başlat</h3>
            <button id="yemekBaslatButonu" class="btn btn-primary">Yemeği Başlat</button>
        </div>

        <div id="overlay" class="overlay">
            <div class="overlay-content">
                <h3>Kamera Açılıyor...</h3>
                <video id="videoElement" width="640" height="480" autoplay></video>
                <button id="startBtn">Yüz Tanımayı Başlat</button>
                <p id="yuzAlgilamaDurumu">Yüz algılanırsa, bilgiler burada gösterilecektir.</p>
                <button id="kapatButonu" class="btn btn-danger">Kapat</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
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
    
    <script src="../js/ana_sayfa.js"></script>
    <script src="../js/app.js"></script>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>



