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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=1">Giriş Yap</a></li>
                <li class="liler" id="signin"><a class="linkler" href="giris_kayit.php?giris=0">Kaydol</a></li>
        </nav>
        <div id="icerik">
        <div id="sonuc" class="ab"></div> 
        <div id= "giris">
        
        <form id="myFormlogin" action="../backend/giris_bcknd.php" method="post">
        <div class='icon_container'>
            <span id='icon_giris'><i id="iconab" class="fas fa-user-circle big-icon"></i></span> 
        </div>
            <div >
                <input type="text" class="form-control" id= "login_ncknm" name="login_kullanici_adi" maxlength="19" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                <span class="icon_user"><i class="fa fa-user"></i></span>
            </div>
            <div>
            <input type="password" id= "login_psw" name="login_sifre" placeholder="Password" maxlength="19">
            <span class="icon_password"><i class="fa fa-lock"></i></span>
            </div>
            <button type="submit" id="loginbutton">Giriş Yap</button>
            <div class="register-link">
                Hesabın yok mu? <a href="giris_kayit.php?giris=0">Kayıt Ol</a>
            </div>
        </form>  
        </div>
        <div id= "kayit">
        <form id="myFormsignup" action="../backend/giris_bcknd.php" method="post"> 
            <h3>Kayıt Ol</h3>
            <div>
            <input type="text" id= "isim" name="isim" maxlength="26" placeholder="İsim"></div>
            <div>
            <input type="text" id= "soyisim" name="soyisim" maxlength="26" placeholder="Soyisim"></div>
            <div>
            <input type="text" id= "ncknm" name="kullanici_adi" maxlength="19" placeholder="Kullanıcı Adı"></div>
            <div>
            <input type="email" id= "mail" name="mail" placeholder="E-Posta" maxlength="28"></div>
            <div>
            <input type="text" id="number" name="number" maxlength="11" placeholder="Telefon Numarası" oninput="this.value = this.value.replace(/[^0-9]/g, '');"></div>
            <div>
            <input type="password" id= "psw" name="sifre" maxlength="19" placeholder="Şifre"></div>
            <button type="submit" id="signupbutton">Kayıt Ol</button>
        </form>   
        </div>
        </div>
    </div>
    <script src="../js/giris_kayit.js"></script>
    <script>
        php_degeral(<?php echo $giris?>);
        
    </script>
    <script src="../js/app.js"></script>
    </body>
</html>