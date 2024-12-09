<?php 
session_start();
include '../backend/uyeler_init_backend.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üyeler</title>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/uyeler.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="hesabim.php">Hesabım</a></li>
                <li class="liler" id = "admin-li"><a class="linkler" href="yemek_takvimi.php">Yemek Takvimi</a></li>
                <li class="prfl" ><div id="prfl-foto"><img src="<?php echo $_SESSION['pp']; ?>"alt="Profil Fotoğrafı" id="prfl-foto-img"></div></li>
                <li class="liler" id="admin-li"><a class="linkler" href="yemekhanem.php">Yemekhanem</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="#">Üyeler</a></li>
                <li class="liler" id="admin-li"><a class="linkler" href="../backend/logout.php">Çıkış</a></li>
        </nav>
        <div id="icerik">
            <div id="sonuc"></div>
        <div id=iletisim_sayfa>
            <div id="mesaj_yaz_div" class="box">
                <span><strong>Öğrencilerinize Mesaj Yazın</strong></span>
                <hr><br><br>
                <form action="../backend/iletisim_backend.php" method="post" id="mesaj_gonder">
                <label for="kategori">Öğrenci Seçin:</label>
                <select name="kategori" id="kategori">
                    <option value="">Seçiniz</option>
                    <?php

                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value='".$row['id']."'>". $row['name'] ." ". $row['surname']."--" .$row['mail']. "</option>";
                    }
                    ?>
                </select><br><br>
                <input type="text" id="konu" name="konu" placeholder="Konu"><br><br>
                <textarea id="mesaj" name="mesaj" placeholder="Mesajınızı buraya yazın" rows="5" cols="40"></textarea>
                <button id='gonder_button' type="submit">Gönder</button>
                </form>
            </div>
            <div id='mesaj_detay' class="box scrollable">
                <div id="ogrenci_gozukuyor">
                <h3>Kullanıcı Bilgileri</h2><hr>
                <?php
                        echo "<span id='oh_isim'>İsim: ".$row_2['name']."<br><hr>Soyisim: ".$row_2['surname']."</span><br><hr>
                        <span id='oh_nickname'>Kullanıcı Adı: ".$row_2['nickname']."</span><br><hr>
                        <span id='oh_mail'>E-Posta: ".$row_2['mail']."</span><br><hr>
                        <span id='oh_tel'>Telefon Numarası: ".$row_2['phonenum']."</span><br><hr>
                        <span id='oh_bakiye'>Bakiye: ".$row_2['moneyy']."</span><br>";    
                    ?>
                </div>
                <div id="mesaj_gozukuyor">
                <h3>Mail Bilgileri</h3><hr>
                    <?php 
                    if($_SESSION['gelgit']==0){
                        $prefix = 'Yazan';
                    }
                    else{
                        $prefix = 'Alıcı';
                    }
                    echo "<span id='mh_kimden'>".$prefix.": ".$row_2['name']." ".$row_2['surname']."</span><br><hr>
                                  <span id='mh_konu'>Konu: ".$row_2['konu']."</span><br><hr>
                                  <span id='mh_mesaj'>Mesaj: ".$row_2['mesaj']."</span><br><hr>";
                    ?>
                    <?php
                    if (isset($row_2['mesaj_saati']) && $row_2['mesaj_saati'] instanceof DateTime) {
                        $mesajSaati = $row_2['mesaj_saati']->format('Y-m-d H:i:s');
                        echo "<span id='mh_saati'>Mesaj Zamanı: " . htmlspecialchars($mesajSaati) . "</span><br>";
                    }
                    ?>
                </div>
                <div id="mesajlar_hakkinda_gozukuyor">
                    <?php
                    if($okunmamis_mesaj==0){
                        echo "<span>Hoş geldiniz ".$row_5['name']." ".$row_5['surname']."<br>Okunmamış mesajınız yok.</span>";
                    }
                    else{
                        echo "<span>Hoş geldiniz ".$row_5['name']." ".$row_5['surname']."<br>".$row_5['okunmamis']." okunmamış mesajınız var.</span>";
                    }
                    
                    ?>
                </div>
            </div> 
            <div id='mesaj_ve_ogrenciler' class="box scrollable">
                <label>
                     <input type="radio" id='tum_radio_butonlar' name="mesaj_tipi" value="uyeler" checked> Üyeler
                </label>
                <label>
                     <input type="radio" id='tum_radio_butonlar' name="mesaj_tipi" value="gelen"> Gelen Mesajlar
                </label>
                <label>
                    <input type="radio" id='tum_radio_butonlar' name="mesaj_tipi" value="giden"> Giden Mesajlar
                </label>
                <div id="uyeler_listesi">
                    <?php
                            while ($row_4 = sqlsrv_fetch_array($stmt_4, SQLSRV_FETCH_ASSOC)) {
                                echo "<div id='okunmus_mesaj' class='okunacak_mesajlar'><a style='text-decoration: none' href='../backend/ogrenci_goster.php?ogrenci_id=".$row_4['id']."&sayfa=uyeler'><span>ID: ". $row_4['id'] ." - ". $row_4['name']." " .$row_4['surname']."</span></a></div><hr>";
                            }
                        ?>
                </div>
                <div id="gelen_mesajlar">
                        <?php
                            while ($row_1 = sqlsrv_fetch_array($stmt_1, SQLSRV_FETCH_ASSOC)) {
                                if($row_1['okundu'] == 'Hayır'){
                                    echo "<div id='okunmus_mesaj' class='okunacak_mesajlar'><a style='text-decoration: none' href='../backend/mesaj_goster.php?mesaj_id=".$row_1['id']."&gelgit=0&sayfa=uyeler'><span>". $row_1['name'] ." ". $row_1['surname']."--" .$row_1['konu']."</span></a></div><hr>";
                                }
                                else{
                                    echo "<div id='okunmamıs_mesaj' class='okunacak_mesajlar'><a style='text-decoration: none' href='../backend/mesaj_goster.php?mesaj_id=".$row_1['id']."&gelgit=0&sayfa=uyeler'><span>". $row_1['name'] ." ". $row_1['surname']."--" .$row_1['konu']."</span></a></div><hr>";
                                }

                            }
                        ?>
                </div>
                <div id="giden_mesajlar">
                        <?php
                            while ($row_3 = sqlsrv_fetch_array($stmt_3, SQLSRV_FETCH_ASSOC)) {
                                echo "<div id='okunmus_mesaj' class='okunacak_mesajlar'><a style='text-decoration: none' href='../backend/mesaj_goster.php?mesaj_id=".$row_3['id']."&gelgit=1&sayfa=uyeler'><span>
                                ". $row_3['name'] ." ". $row_3['surname']."--" .$row_3['konu']."</span></a></div><hr>";

                            }
                        ?>
                </div>
            </div>
        </div>

        </div>
    </div>
    <script src="../js/uyeler_app.js"></script>
    <script>
        deger_al(<?php echo $_SESSION['suanki_mesaj']?>);
    </script>
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>