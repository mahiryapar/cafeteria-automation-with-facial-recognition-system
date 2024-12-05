<?php
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
        }

?>
    