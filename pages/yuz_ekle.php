<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yüz Ekle</title>
    <link rel="stylesheet" href="../css/design.css">
    <link rel="stylesheet" href="../css/yuz_ekle.css">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script src="../js/face-api.js"></script> 
    <style>
        #sonuc{
            display:none;
            margin-left:20vw;
            margin-right:20vw;
            margin-top:3vh;
        }
        #videoElement {
            width: 100%;
            max-width: 500px;
            min-width: 200px;
            height: 100%;
            min-height:200px;
            border: 2px solid #ccc;
            margin-top: 20px;
        }
        #overlay {
            display: none;
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
        <div id='yuz_tanima_div'>
            <center>
                <p id="message">Lütfen Yüz Tanımanın Düzgün Çalışması İçin Kameraya Bakınız</p>
                <video id="videoElement" autoplay></video><br>
                <button id="startBtn">Yüz Tanımayı Başlat</button>
            </center>
        </div> 
        
        
        <div id="overlay"></div>
        </div>
    </div>
    
    <script src="../js/app.js"></script>
    <script src="../js/yuz_ekle.js"></script>
    </body> 
</html>