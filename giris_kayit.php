<?php
header('Content-Type: text/html; charset=utf-8');
if(isset($_GET['giris'])){
    $giris = $_GET['giris'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if($giris ==1){echo "Giriş Yap";}else{echo "Kayıt Ol";} ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
        </nav>
        <div id="icerik">
        <div id= "giris">
        <form id="myFormlogin" action="giris_bcknd.php" method="post"> 
            <div><span style="color:black">Kullanıcı Adı: </span>       
            <input type="text" id= "login_ncknm" name="login_kullanici_adi"></div>
            <div><span style="color:black">Şifre:</span>
            <input type="password" id= "login_psw" name="login_sifre"></div>
            <button type="submit" id="loginbutton">Onayla</button>
        </form>  
        </div>
        <div id= "kayit">
        <form id="myFormsignup" action="giris_bcknd.php" method="post"> 
            <div><span style="color:black">İsim: </span>
            <input type="text" id= "isim" name="isim"></div>
            <div><span style="color:black">Soyisim: </span>
            <input type="text" id= "soyisim" name="soyisim"></div>
            <div><span style="color:black">Kullanıcı Adı: </span>
            <input type="text" id= "ncknm" name="kullanici_adi"></div>
            <div><span style="color:black">Mail: </span>
            <input type="email" id= "mail" name="mail"></div>
            <div><span style="color:black">Telefon Numarası: </span>
            <input type="number" id= "number" name="number"></div>
            <div><span style="color:black">Şifre:</span>
            <input type="password" id= "psw" name="sifre"></div>
            <button type="submit" id="signupbutton">Onayla</button>
        </form>   
        </div>
        <div id="sonuc"></div> 
        </div>
    </div>
    <script>
        console.log(<?php echo $giris?>);
        if(<?php echo $giris?> == 0){
            document.getElementById("kayit").style.display  = "block";
            document.getElementById("myFormsignup").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("giris_bcknd.php?giris=0", {
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
        }
        else{      
            document.getElementById("giris").style.display = "block";
            document.getElementById("myFormlogin").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            fetch("giris_bcknd.php?giris=1", {
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
        }       
</script>
    <script src="app.js"></script>
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="giris_kayit_design.css">
</html>