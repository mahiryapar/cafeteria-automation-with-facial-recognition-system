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
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/giris_kayit_design.css">
    <style>
        #sonuc{
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
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
        </nav>
        <div id="icerik">
        <div id="sonuc"></div> 
        <div id= "giris">
        <form id="myFormlogin" action="../backend/giris_bcknd.php" method="post"> 
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input type="text" class="form-control" id= "login_ncknm" name="login_kullanici_adi" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div><span style="color:black">Şifre:</span>
            <input type="password" id= "login_psw" name="login_sifre"></div>
            <button type="submit" id="loginbutton">Onayla</button>
        </form>  
        </div>
        <div id= "kayit">
        <form id="myFormsignup" action="../backend/giris_bcknd.php" method="post"> 
            <div><span style="color:black">İsim: </span>
            <input type="text" id= "isim" name="isim"></div>
            <div><span style="color:black">Soyisim: </span>
            <input type="text" id= "soyisim" name="soyisim"></div>
            <div><span style="color:black">Kullanıcı Adı: </span>
            <input type="text" id= "ncknm" name="kullanici_adi"></div>
            <div><span style="color:black">Mail: </span>
            <input type="email" id= "mail" name="mail"></div>
            <div><span style="color:black">Telefon Numarası: </span>
            <input type="text" id="number" name="number" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');"></div>
            <div><span style="color:black">Şifre:</span>
            <input type="password" id= "psw" name="sifre"></div>
            <button type="submit" id="signupbutton">Onayla</button>
        </form>   
        </div>
        </div>
    </div>
    <script src="../js/giris_kayit.js"></script>
    <script>
        php_degeral(<?php echo $giris?>);
        
    </script>
    <script src="../js/app.js"></script>
   
</html>