<?php 
session_start();
include '../backend/yemek_takvimi_info_init_backend.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Takvimi</title>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/yemek_takvimi_info_design.css">
    <link href ="../css/yemek_takvimi_design.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ogrenci-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "ortak-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
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
            <div id="sonuc"></div>
            <span id="warn">Şu anda bir yemekhaneye kayıtlı değilsiniz.</span>
            <span>Tarih: <?php echo $date?></span>
            <div id="yemekler">
            <div id="kahvalti">
                <span>Kahvaltı:<br> </span>
                <span>
                    Ana yemek: <?php echo !empty($kahvalti_ana_yemek) ? $kahvalti_ana_yemek : "Yok"; ?><br> 
                    Açıklama: <?php echo !empty($kahvalti_ana_yemek_aciklama) ? $kahvalti_ana_yemek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_ana_yemek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Kahvaltılık 1: <?php echo !empty($kahvalti_ara_sicak) ? $kahvalti_ara_sicak : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_ara_sicak_aciklama) ? $kahvalti_ara_sicak_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_ara_sicak] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Kahvaltılık 2: <?php echo !empty($kahvalti_corba) ? $kahvalti_corba : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_corba_aciklama) ? $kahvalti_corba_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_corba] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Kahvaltılık 3: <?php echo !empty($kahvalti_tatli) ? $kahvalti_tatli : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_tatli_aciklama) ? $kahvalti_tatli_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_tatli] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    İçecek: <?php echo !empty($kahvalti_icecek) ? $kahvalti_icecek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($kahvalti_icecek_aciklama) ? $kahvalti_icecek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$kahvalti_icecek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <div class="yorum_yaz">
                    <form id="kahvalti_yorum_yaz" action="../backend/yorum_ekle.php" method="post">
                    <input type="hidden" name="form_id" value="kahvalti_yorum_yaz">
                    <textarea id="yorum_input" name="yazilan_yorum" placeholder="Yorumunuzu yazın.." rows="5" cols="50"></textarea>
                        <button type="submit">Yorumu gönder</button>
                    </form>
                </div>
                <div class="yorumlar">
                    <h4>Yorumlar:</h4>
                    <ul class="yorumlar_ul"></ul>
                    <?php 
                    $kahvalti_yorumlar = yorumlariGetir($_SESSION['kahvalti_menu_id'], $conn); 
                    if (!empty($kahvalti_yorumlar)) {
                        foreach ($kahvalti_yorumlar as $yorum) {
                            echo "<li class='yorumlar_li'><b>{$yorum['sahibi_nickname']}:</b> {$yorum['yorum']}</p>";
                        }
                    } else {
                        echo "<p>Henüz yorum yok.</p>";
                    }
                    ?>
                </div>
            </div>
            <div id="ogle">
                <span>Öğle Yemeği: <br> </span>
                <span>
                    Ana yemek: <?php echo !empty($ogle_yemegi_ana_yemek) ? $ogle_yemegi_ana_yemek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_ana_yemek_aciklama) ? $ogle_yemegi_ana_yemek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_ana_yemek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Ara sıcak: <?php echo !empty($ogle_yemegi_ara_sicak) ? $ogle_yemegi_ara_sicak : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_ara_sicak_aciklama) ? $ogle_yemegi_ara_sicak_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_ara_sicak] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Çorba: <?php echo !empty($ogle_yemegi_corba) ? $ogle_yemegi_corba : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_corba_aciklama) ? $ogle_yemegi_corba_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_corba] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Tatlı: <?php echo !empty($ogle_yemegi_tatli) ? $ogle_yemegi_tatli : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_tatli_aciklama) ? $ogle_yemegi_tatli_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_tatli] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    İçecek: <?php echo !empty($ogle_yemegi_icecek) ? $ogle_yemegi_icecek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($ogle_yemegi_icecek_aciklama) ? $ogle_yemegi_icecek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$ogle_yemegi_icecek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <div class="yorum_yaz">
                    <form id="ogle_yorum_yaz" action="../backend/yorum_ekle.php" method="post">
                    <input type="hidden" name="form_id" value="ogle_yorum_yaz">
                    <textarea id="yorum_input" name="yazilan_yorum" placeholder="Yorumunuzu yazın.." rows="5" cols="50"></textarea>
                        <button type="submit">Yorumu gönder</button>
                    </form>
                </div>
                <div class="yorumlar">
                    <h4>Yorumlar:</h4>
                    <ul class="yorumlar_ul"></ul>
                    <?php 
                    $ogle_yorumlar = yorumlariGetir($_SESSION['ogle_yemegi_menu_id'], $conn); 
                    if (!empty($ogle_yorumlar)) {
                        foreach ($ogle_yorumlar as $yorum) {
                            echo "<li class='yorumlar_li'><b>{$yorum['sahibi_nickname']}:</b> {$yorum['yorum']}</li>";
                        }
                    } else {
                        echo "<p>Henüz yorum yok.</p>";
                    }
                    ?>
                </div>
            </div>
            <div id="aksam">
                <span>Akşam Yemeği: <br> </span>
                <span>
                    Ana yemek: <?php echo !empty($aksam_yemegi_ana_yemek) ? $aksam_yemegi_ana_yemek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_ana_yemek_aciklama) ? $aksam_yemegi_ana_yemek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_ana_yemek]?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Ara sıcak: <?php echo !empty($aksam_yemegi_ara_sicak) ? $aksam_yemegi_ara_sicak : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_ara_sicak_aciklama) ? $aksam_yemegi_ara_sicak_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_ara_sicak] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Çorba: <?php echo !empty($aksam_yemegi_corba) ? $aksam_yemegi_corba : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_corba_aciklama) ? $aksam_yemegi_corba_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_corba] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    Tatlı: <?php echo !empty($aksam_yemegi_tatli) ? $aksam_yemegi_tatli : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_tatli_aciklama) ? $aksam_yemegi_tatli_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_tatli] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <span>
                    İçecek: <?php echo !empty($aksam_yemegi_icecek) ? $aksam_yemegi_icecek : "Yok"; ?><br>
                    Açıklama: <?php echo !empty($aksam_yemegi_icecek_aciklama) ? $aksam_yemegi_icecek_aciklama : "Yok"; ?><br>
                </span>
                <div class="yemekimgdiv" ><img class="yemekimg" src="<?php echo $resimler[$aksam_yemegi_icecek] ?>" alt="" onerror="this.style.visibility='hidden';"></div>
                <div class="yorum_yaz">
                    <form id="aksam_yorum_yaz" action="../backend/yorum_ekle.php" method="post">
                    <input type="hidden" name="form_id" value="aksam_yorum_yaz">    
                    <textarea id="yorum_input" name="yazilan_yorum" placeholder="Yorumunuzu yazın.." rows="5" cols="50"></textarea>
                        <button type="submit">Yorumu gönder</button>
                    </form>
                </div>
                <div class="yorumlar">
                    <h4>Yorumlar:</h4>
                    <ul class="yorumlar_ul"></ul>
                    <?php 
                    $aksam_yorumlar = yorumlariGetir($_SESSION['aksam_yemegi_menu_id'], $conn); 
                    if (!empty($aksam_yorumlar)) {
                        foreach ($aksam_yorumlar as $yorum) {
                            echo "<li class='yorumlar_li'><b>{$yorum['sahibi_nickname']}:</b> {$yorum['yorum']}</li>";
                        }
                    } else {
                        echo "<p>Henüz yorum yok.</p>";
                    }
                    ?>
                </div>            
            </div>
            </div>
        </div>
    </div>
    <script src="../js/yemek_takvimi_info.js"></script>
    <script>
        deger_al(<?php echo $yemekhane_id?>,<?php echo $kahv?>,<?php echo $ogle?>,<?php echo $aksam?>);
    </script>
    <script src="../js/app.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>