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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
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
            <span id="mevcut_bakiye">Şu anki bakiyeniz: <?php include '../backend/bakiye_bilgisi_cek.php'?></span>
            <form id="para_yukle_form" action="../backend/bakiye_yukle_backend.php" method="post"><br>
                <input type="number" id="kart_no" name="kart_no" placeholder="kart numarası"><br>
                <input type="text"   id="skt" name="skt" placeholder="son kullanma tarihi"><br>
                <input type="number" id="cvv" name="cvv" placeholder="cvv kodu"><br>
                <input type="number" id="bakiye" name="bakiye"placeholder="yüklemek istediğin bakiye"><br>
                <button type="submit">Para Yükle</button><br>
            </form>

        </div>
    </div>
    <script>
        document.getElementById("para_yukle_form").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("../backend/bakiye_yukle_backend.php", {
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

