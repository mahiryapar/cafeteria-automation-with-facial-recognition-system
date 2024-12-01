<?php
session_start();
if(isset($_SESSION['nickname'])){
    $_SESSION['nickname'] = "default_pp";
    $_SESSION['role'] = "guest";
    $_SESSION['flash_message'] = "Başarıyla çıkış yaptınız!";
    unset($_SESSION['suanki_mesaj']);
    unset($_SESSION['isim']);
    unset($_SESSION['soyisim']);
    unset($_SESSION['mail']);
    unset($_SESSION['tel']);
    unset($_SESSION['bakiye']);
    unset($_SESSION['yemekhane']);
    unset($_SESSION['yemekhane_id']);
    unset($_SESSION['user_id']);    

    
    header("Location: ../pages/index.php");
    exit(1);
    
}
else{
    $_SESSION['flash_message'] = "Hata! Zaten çıkış yapıldı!";
    header("Location: ../pages/index.php");
    exit(1);
}
?>