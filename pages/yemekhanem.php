<?php 
session_start();
include '../backend/yemekhanem_backend.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekhanem</title>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/yemekhanem.css">
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
        <?php if(isset($_GET['message']) && $_GET['message']=="Onaylandı"):?>
            <?php echo alertver("success","Başarılı","Katılma isteği onaylandı.");?>
        <?php elseif(isset($_GET['message']) && $_GET['message']=="Reddedildi"): ?>
            <?php echo alertver("success","Başarılı","Katılma isteği reddedildi.");?>
        <?php elseif(isset($_GET['message']) && $_GET['message']=="IstekGonderildi"): ?>
            <?php echo alertver("success","Başarılı","Katılma isteği gönderildi.");?>
        <?php elseif(isset($_GET['message']) && $_GET['message']=="OgrenciKaldirildi"): ?>
            <?php echo alertver("success","Başarılı","Öğrenci kaldırıldı.");?>
        <?php elseif(isset($_GET['message']) && $_GET['message']=="YemekhaneOlusturuldu"): ?>
            <?php echo alertver("success","Başarılı","Yemekhane başarıyla oluşturuldu.");?>
            <?php elseif(isset($_GET['message']) && $_GET['message']=="menueklendi"): ?>
                <?php echo alertver("success","Başarılı","Menü Eklendi.");?>
        <?php endif;?>
        <div id="icerik">
            <div id="anakart" class="containerr">
            <?php if ($_SESSION['yemekhane_id'] == 1&&$_SESSION['role'] =="ogrenci"): ?>    
                <div id="yemekhane_yok" class="box">
                <h3>Henüz bir yemekhaneye bağlı değilsiniz.</h3>

                <?php if ($istek): ?>
                    <p>Katılma isteğiniz onay bekliyor: <strong><?php echo htmlspecialchars($istek['yemekhane_ismi']); ?></strong></p>
                <?php else: ?>
                    <form action="../backend/join_yemekhane.php" method="POST">
                        <div class="form-group">
                            <label for="yemekhane">Yemekhane Seç:</label>
                            <select name="yemekhane_id" id="yemekhane" class="form-control" required>
                                <option value="" disabled selected>Yemekhane seçiniz</option>
                                <?php foreach ($yemekhaneler as $yemekhane): ?>
                                    <option value="<?php echo $yemekhane['id']; ?>">
                                        <?php echo htmlspecialchars($yemekhane['isim']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Katıl</button>
                    </form>
                <?php endif; ?>
            </div>
            <?php elseif($_SESSION['yemekhane_id'] != 1): ?>
                <div id="yemekhane_bilgileri" class="box">
                    <h3>Yemekhanenizin Bilgileri:</h3>
                    <p><strong>İsim:</strong> <?php echo $yemekhane['isim']; ?></p>
                    <p><strong>Kurum:</strong> <?php echo $yemekhane['kurum']; ?></p>
                    <p><strong>Kapasite:</strong> <?php echo $yemekhane['kapasite']; ?></p>
                    <p><strong>Adres:</strong> <?php echo $yemekhane['adres']; ?></p>
                    <?php if (!empty($ogunler)): ?>
                    <h4>Öğün Bilgileri:</h4>
                        <ul>
                            <?php foreach ($ogunler as $ogun): ?>
                                <li>
                                    <?php
                                    if (isset($ogun['ogun_saati']) && isset($ogun['ogun_bitis_saati'])) {
                                        $ogunsaatiRaw = explode('.', $ogun['ogun_saati'])[0];
                                        $ogunbitissaatiRaw = explode('.', $ogun['ogun_bitis_saati'])[0];
                                        $ogunsaati = DateTime::createFromFormat('H:i:s', $ogunsaatiRaw);
                                        $ogunbitissaati = DateTime::createFromFormat('H:i:s', $ogunbitissaatiRaw);
                                    }
                                    ?>
                                    <strong>Öğün:</strong> <?php echo htmlspecialchars($ogun['ogun']); ?><br>
                                    <strong>Başlangıç Saati:</strong> <?php echo $ogunsaati->format('H:i'); ?><br>
                                    <strong>Bitiş Saati:</strong> <?php echo $ogunbitissaati->format('H:i'); ?><br>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Bu yemekhane için öğün bilgisi bulunmamaktadır.</p>
                    <?php endif; ?>
                </div> 
            <?php endif; ?>
            <?php if($_SESSION['role'] === 'admin'): ?>
            <?php if ($_SESSION['yemekhane_id'] == 1): ?>

                <div id="yemekhane_olustur" class="box">
                    <h3>Yemekhaneniz bulunmuyor. Yeni bir yemekhane oluşturabilirsiniz:</h3>
                    <form action="../backend/create_yemekhane.php" method="POST">
                        <div class="form-group">
                            <label for="isim">Yemekhane İsmi:</label>
                            <input type="text" name="isim" id="isim" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="kurum">Kurum:</label>
                            <input type="text" name="kurum" id="kurum" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="kapasite">Kapasite:</label>
                            <input type="number" name="kapasite" id="kapasite" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="adres">Adres:</label>
                            <textarea name="adres" id="adres" class="form-control" required></textarea>
                        </div>
                        <div class="form-group mt-4">
                            <h4>Öğünler:</h4>
                            <div>
                                <input type="checkbox" id="kahvalti" name="ogunler[Kahvaltı][secilen]" value="1">
                                <label for="kahvalti">Kahvaltı</label>
                                <br>
                                <label for="kahvalti_baslangic">Başlangıç Saati:</label>
                                <input type="time" id="kahvalti_baslangic" name="ogunler[Kahvaltı][baslangic]">
                                <label for="kahvalti_bitis">Bitiş Saati:</label>
                                <input type="time" id="kahvalti_bitis" name="ogunler[Kahvaltı][bitis]">
                            </div>
                            <div class="mt-2">
                                <input type="checkbox" id="ogle" name="ogunler[Öğle Yemeği][secilen]" value="1">
                                <label for="ogle">Öğle Yemeği</label>
                                <br>
                                <label for="ogle_baslangic">Başlangıç Saati:</label>
                                <input type="time" id="ogle_baslangic" name="ogunler[Öğle Yemeği][baslangic]">
                                <label for="ogle_bitis">Bitiş Saati:</label>
                                <input type="time" id="ogle_bitis" name="ogunler[Öğle Yemeği][bitis]">
                            </div>
                            <div class="mt-2">
                                <input type="checkbox" id="aksam" name="ogunler[Akşam Yemeği][secilen]" value="1">
                                <label for="aksam">Akşam Yemeği</label>
                                <br>
                                <label for="aksam_baslangic">Başlangıç Saati:</label>
                                <input type="time" id="aksam_baslangic" name="ogunler[Akşam Yemeği][baslangic]">
                                <label for="aksam_bitis">Bitiş Saati:</label>
                                <input type="time" id="aksam_bitis" name="ogunler[Akşam Yemeği][bitis]">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Yemekhane Oluştur</button>
                    </form>
                </div>
            <?php else: ?>
            <div class="small-box-container">
            <div id="ogrenciler" class="box">
                <h4>Bağlı Öğrenciler:</h4>
                <ul>
                    <?php
                    while ($ogrenci = sqlsrv_fetch_array($stmt_ogrenci, SQLSRV_FETCH_ASSOC)): ?>
                        <li>
                            <?php echo $ogrenci['name']; ?> - <?php echo $ogrenci['surname']; ?> 
                            - <a href="../backend/remove_student.php?user_id=<?php echo $ogrenci['id']; ?>" onclick="return confirm('Bu öğrenciyi yemekhaneden kaldırmak istediğinize emin misiniz?');">Kaldır</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div id="istekler" class="box">
                <h4>Katılma İstekleri:</h4>
                <ul>
                    <?php
                    while ($istek = sqlsrv_fetch_array($stmt_istek, SQLSRV_FETCH_ASSOC)): ?>
                        <li>
                            <?php echo $istek['name']." ".$istek['surname'] ?> 
                            - <a href="../backend/approve_request.php?id=<?php echo $istek['id']; ?>">Onayla</a>
                            - <a href="../backend/reject_request.php?id=<?php echo $istek['id']; ?>">Reddet</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            </div>
            <div id="menu_ekle" class="box">
                <h4>Yeni Menü Ekle:</h4>
                <form action="../backend/add_menu.php" method="POST">
                    <?php
                    while ($yemek = sqlsrv_fetch_array($stmtYemekler, SQLSRV_FETCH_ASSOC)) {
                        $kategori = $yemek['kategori'];
                        if (isset($yemekKategorileri[$kategori])) {
                            $yemekKategorileri[$kategori][] = $yemek['yemek_ismi'];
                        }
                    }
                    ?>
                    <div class="form-group">
                        <label for="tarih">Tarih:</label>
                        <input type="date" name="tarih" id="tarih" class="form-control" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="ogun">Öğün:</label>
                        <select name="ogun" id="ogun" class="form-control" required>
                            <option value="" disabled selected>Öğün seçiniz</option>
                            <?php while ($ogun = sqlsrv_fetch_array($stmtOgünler, SQLSRV_FETCH_ASSOC)): ?>
                                <option value="<?php echo $ogun['ogun']; ?>">
                                    <?php echo $ogun['ogun']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="fiyat">Menü Fiyatı (₺):</label>
                        <input type="number" name="fiyat" id="fiyat" class="form-control" required>
                    </div>
                    <div id="yemek_secenekleri">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Menüyü Ekle</button>
                </form>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../js/yemekhanem.js"></script>
    <script>
        deger_al( <?php echo json_encode($yemekKategorileri); ?>)
    </script>
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
