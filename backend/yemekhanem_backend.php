<?php
function alertver($type,$about_mesaj,$mesaj){
    return "<div id='alertdiv' class='alert alert-$type'>
                <strong>$about_mesaj!</strong> $mesaj
                <script>
                    setTimeout(() => {
                        document.getElementById('alertdiv').style.display = 'none';
                        window.location.href = 'yemekhanem.php';
                    }, 2000);    
                </script>
            </div>";
}
        
        $connection_alert;
        $query;
        session_start();
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
        
        if( $conn ) {
             $connection_alert = "Connection established.<br />";
             $sql = "select name,surname ,yemekhane_ogunleri.ogun,yemekhane_ogunleri.ogun_saati,yemekhane_ogunleri.ogun_bitis_saati
                    from Users
                    inner join yemekhaneler
                    on yemekhaneler.id = users.yemekhane_id
                    inner join yemekhane_ogunleri 
                    on yemekhaneler.id = yemekhane_ogunleri.yemekhane_id
                    where users.id = ? ";
             $params = [$_SESSION['user_id']];
             $stmt = sqlsrv_query($conn, $sql,$params);

            if ($stmt === false) {
                 die(print_r(sqlsrv_errors(), true));
            }
            $_SESSION['ogunler'] = [];
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                $_SESSION['isim'] = $row['name'];
                $_SESSION['soyisim'] = $row['surname'];
                $_SESSION['ogunler'][] = [
                    'ogun' => $row['ogun'],
                    'ogun_saati' => $row['ogun_saati'],
                    'ogun_bitis_saati' => $row['ogun_bitis_saati']
                ];
            }
            $sql = "SELECT * FROM yemekhaneler";
            $stmt = sqlsrv_query($conn, $sql);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $yemekhaneler = [];
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                if($row['id'] != 1){
                    $yemekhaneler[] = [
                        "isim" => $row['isim'],
                        "id" => $row['id']
                    ];
                } 
            }
            $yemekhane = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $sql = "SELECT * FROM yemekhaneler WHERE id = ?";
            $params = [$_SESSION['yemekhane_id']];
            $stmt = sqlsrv_query($conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $yemekhane = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $sql_ogrenci = "SELECT * FROM users WHERE yemekhane_id = ? and rol='ogrenci'";
            $params = [$_SESSION['yemekhane_id']];
            $stmt_ogrenci = sqlsrv_query($conn, $sql_ogrenci, $params);
            if ($stmt_ogrenci === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $sql_istek = "SELECT users.name,users.surname,katilma_istekleri.id FROM katilma_istekleri 
                    inner join users
                    on users.id = katilma_istekleri.user__id
                    WHERE katilma_istekleri.yemekhane_id = ?";
            $params_istek = [$_SESSION['yemekhane_id']];
            $stmt_istek = sqlsrv_query($conn, $sql_istek, $params_istek);
            if ($stmt_istek === false) {
                die(print_r(sqlsrv_errors(), true));
            }
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


        }

?>
