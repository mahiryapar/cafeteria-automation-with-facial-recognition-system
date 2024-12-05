<?php 
session_start();
include 'yemekhanem_backend.php';
$configPath ='../config/database_infos.json';
if (!file_exists($configPath)) {
    die('Config dosyası bulunamadı.');
}
$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    die('Config dosyası okunamadı veya geçersiz JSON formatı.');
}
$serverName = $config['db_host']; 
$database = $config['db_name'];
$uid = $config['db_user'];
$pass = $config['db_password'];
$connection_info = [
    "Database" =>   $database,
    "Uid" => $uid,
    "PWD" => $pass,
    "CharacterSet"=>"UTF-8",
    'ReturnDatesAsStrings'=>true
];
$conn = sqlsrv_connect($serverName,$connection_info);
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
        <div id="icerik" class="container">
        <?php if ($_SESSION['yemekhane_id'] == null): ?>    
                <div id="yemekhane_yok" class="box">
                    <h3>Henüz bir yemekhaneye bağlı değilsiniz.</h3>
                    <form action="../backend/join_yemekhane.php" method="POST">
                        <div class="form-group">
                            <label for="yemekhane">Yemekhane Seç:</label>
                            <select name="yemekhane_id" id="yemekhane" class="form-control" required>
                                <option value="" disabled selected>Yemekhane seçiniz</option>
                                <?php foreach ($yemekhaneler as $yemekhane): ?>
                                    <option value="<?php echo $yemekhane['id']; ?>">
                                        <?php echo $yemekhane['isim']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Katıl</button>
                    </form>
                </div>
            <?php else: ?>
                <div id="yemekhane_bilgileri" class="box">
                <h3>Yemekhanenizin Bilgileri:</h3>
                <?php
                $sql = "SELECT * FROM yemekhaneler WHERE id = ?";
                $params = [$_SESSION['yemekhane_id']];
                $stmt = sqlsrv_query($conn, $sql, $params);
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                $yemekhane = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                ?>
                <p><strong>İsim:</strong> <?php echo $yemekhane['isim']; ?></p>
                <p><strong>Kurum:</strong> <?php echo $yemekhane['kurum']; ?></p>
                <p><strong>Kapasite:</strong> <?php echo $yemekhane['kapasite']; ?></p>
                <p><strong>Adres:</strong> <?php echo $yemekhane['adres']; ?></p>
        </div>
        <?php endif; ?>
        <?php if($_SESSION['role'] === 'admin'): ?>
        <?php if ($_SESSION['yemekhane_id'] == 1): ?>
        <!-- Yemekhane Oluşturma -->
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
                <button type="submit" class="btn btn-primary mt-3">Yemekhane Oluştur</button>
            </form>
        </div>
    <?php else: ?>
        <div id="ogrenciler" class="box">
            <h4>Bağlı Öğrenciler:</h4>
            <ul>
                <?php
                $sql = "SELECT * FROM users WHERE yemekhane_id = ? and rol='ogrenci'";
                $stmt = sqlsrv_query($conn, $sql, $params);
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                while ($ogrenci = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <li><?php echo $ogrenci['name']; ?> - <?php echo $ogrenci['surname']; ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
        <div id="istekler" class="box">
            <h4>Katılma İstekleri:</h4>
            <ul>
                <?php
                $sql = "SELECT * FROM katilma_istekleri 
                inner join users
                on users.id = katilma_istekleri.user__id
                WHERE katilma_istekleri.yemekhane_id = ?";
                $params = [$_SESSION['yemekhane_id']];
                $stmt = sqlsrv_query($conn, $sql, $params);
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                while ($istek = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <li>
                        <?php echo $istek['name']." ".$istek['surname'] ?> 
                        - <a href="../backend/approve_request.php?id=<?php echo $istek['id']; ?>">Onayla</a>
                        - <a href="../backend/reject_request.php?id=<?php echo $istek['id']; ?>">Reddet</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
        <div id="menu_ekle" class="box scrollable">
            <h4>Yeni Menü Ekle:</h4>
            <form action="../backend/add_menu.php" method="POST">
                <?php
                $yemekhane_id = $_SESSION['yemekhane_id'];
                $sqlOgünler = "SELECT ogun FROM yemekhane_ogunleri WHERE yemekhane_id = ?";
                $stmtOgünler = sqlsrv_query($conn, $sqlOgünler, [$yemekhane_id]);
                if ($stmtOgünler === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                $sqlYemekler = "SELECT yemek_ismi, kategori FROM yemekler";
                $stmtYemekler = sqlsrv_query($conn, $sqlYemekler);
                if ($stmtYemekler === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                $yemekKategorileri = [
                    'Ana Yemek' => [],
                    'Ara Sıcak' => [],
                    'Çorba' => [],
                    'Tatlı' => [],
                    'İçecek' => []
                ];
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
    <script>
        window.onload = () => {
            if(<?php echo json_encode($message);?> != null){
                document.getElementById('cikis').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('cikis').style.display = 'none';
                }, 2000);
                const ogun = document.getElementById('ogun')
        }
        };
        document.addEventListener('DOMContentLoaded', function () {
            const ogunSelect = document.getElementById('ogun');
            ogunSelect.addEventListener('change', updateInputs);
        });
        const yemekler = <?php echo json_encode($yemekKategorileri); ?>;
        function updateInputs() {
            const ogun = document.getElementById('ogun').value;
            const yemekSecenekleriDiv = document.getElementById('yemek_secenekleri');
            yemekSecenekleriDiv.innerHTML = ''; 
            if (ogun === 'Kahvaltı') {
                addDropdown('Ana Yemek', 'ana_yemek','Ana Yemek');
                addDropdown('Kahvaltılık 1', 'kahvaltilik_1', 'Ara Sıcak');
                addDropdown('Kahvaltılık 2', 'kahvaltilik_2', 'Ara Sıcak');
                addDropdown('Kahvaltılık 3', 'kahvaltilik_3', 'Ara Sıcak');
                addDropdown('İçecek', 'icecek', 'İçecek');
            } else {
                addDropdown('Ana Yemek', 'ana_yemek','Ana Yemek');
                addDropdown('Ara Sıcak', 'ara_sicak', 'Ara Sıcak');
                addDropdown('Çorba', 'corba', 'Çorba');
                addDropdown('Tatlı', 'tatli', 'Tatlı');
                addDropdown('İçecek', 'icecek', 'İçecek');
            }
        }
        function addDropdown(labelText, name, kategori = null) {
            const yemekSecenekleriDiv = document.getElementById('yemek_secenekleri');
            const label = document.createElement('label');
            label.textContent = labelText + ':';
            label.className = 'form-label mt-3';
            const select = document.createElement('select');
            select.name = name;
            select.id = name;
            select.className = 'form-control';
            select.required = true;
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            defaultOption.textContent = labelText + ' seçiniz';
            select.appendChild(defaultOption);
            if (kategori && yemekler[kategori]) {
                yemekler[kategori].forEach(yemek => {
                    const option = document.createElement('option');
                    option.value = yemek;
                    option.textContent = yemek;
                    select.appendChild(option);
                });
            } else if (!kategori) {
                Object.values(yemekler).flat().forEach(yemek => {
                    const option = document.createElement('option');
                    option.value = yemek;
                    option.textContent = yemek;
                    select.appendChild(option);
                });
            }
            yemekSecenekleriDiv.appendChild(label);
            yemekSecenekleriDiv.appendChild(select);
        }
    </script>
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
