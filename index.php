
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="design.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler"><a class="linkler" href="#">Ana Sayfa</a></li>
                <li class="liler"><a class="linkler" href="#">Yemek Takvimi</a></li>
                <li class="prfl" ><div id="prfl-foto"></div></li>
                <li class="liler"><a class="linkler" href="#">Öğrenciler</a></li>
                <li class="liler"><a class="linkler" href="#">İletişim</a></li>
        </nav>
        <div id="icerik">
        <?php
        $serverName = "34.159.4.220"; 
        $database = "db_yemekhane";
        $uid = "sqlserver";
        $pass = "admin";
        $connection_info = [
            "Database" =>   $database,
            "Uid" => $uid,
            "PWD" => $pass
        ];
        $conn = sqlsrv_connect($serverName,$connection_info);
        
        if( $conn ) {
             echo "Connection established.<br />";
             $sql = "SELECT * FROM Users"; // tabloadi yerine kendi tablonuzun adını yazın
             $stmt = sqlsrv_query($conn, $sql);

            if ($stmt === false) {
                 die(print_r(sqlsrv_errors(), true));
             }

            // Verileri okuma
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                 echo "ID: " . $row['id'] . " - Ad: " . $row['name'] . " - Soyad: " . $row['surname'].
                  " - Mail: " . $row['mail'] . " - Tel: " . $row['phonenum']. " - Money: " . $row['moneyy']."<br />"; // Kendi sütun adlarınıza göre değiştirin
            }

             // Kaynakları serbest bırak
             sqlsrv_free_stmt($stmt);
        }else{
            echo "Connection could not be established.<br />";
            die( print_r( sqlsrv_errors(), true));
        }
        ?>
            <!--
            <div class="icerikdivleri" id="hosgeldiniz">
                <span class="metin" data-original="Merhabalar!">Merhabalar!</span>

            </div>
            <div class="icerikdivleri" id="bilgiler">
                <span class="metin" data-original="Necmettin Erbakan Bilgisayar Mühendisliği">Necmettin Erbakan Bilgisayar Mühendisliği</span>

            </div>
            <div class="icerikdivleri" id="baglantilar">
                <span class="metin" data-original="github insta cart curt">github insta cart curt</span>
            </div>-->
        </div>
    </div>
    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>