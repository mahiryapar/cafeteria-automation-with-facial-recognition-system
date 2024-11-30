<?php
        $connection_alert;
        $query;
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
            "CharacterSet"=>"UTF-8"
        ];
        $conn = sqlsrv_connect($serverName,$connection_info);
        
        if( $conn ) {
             $connection_alert = "Connection established.<br />";
             $sql = "SELECT name,surname,mail,phonenum,moneyy,yemekhaneler.isim FROM Users
                     inner join yemekhaneler 
                     on yemekhane_id = yemekhaneler.id
                     where nickname = ?";
             $params = [$_SESSION['nickname']];
             $stmt = sqlsrv_query($conn, $sql,$params);

            if ($stmt === false) {
                 die(print_r(sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            session_start();
            $_SESSION['isim'] = $row['name'];
            $_SESSION['soyisim'] = $row['surname'];
            $_SESSION['bakiye'] = $row['moneyy'];
            $_SESSION['mail'] = $row['mail'];
            $_SESSION['tel'] = $row['phonenum'];
            $_SESSION['yemekhane'] = $row['isim'];
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);
        }else{
            $connection_alert = "Connection could not be established.<br />";
            die( print_r( sqlsrv_errors(), true));
        }
?>