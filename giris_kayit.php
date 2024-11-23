<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div id="sayfa">
        <nav id="nav">
            <ul id="liste">
                <li class="liler" id = "ortak-li"><a class="linkler" href="index.php">Ana Sayfa</a></li>
                <li class="liler" id = "ortak-li"><a class="linkler" href="#">Yemek Takvimi</a></li>
                <li class="liler" id="signin"><a class="linkler" href="#">Giriş Yap</a></li>
                <li class="liler" id="signin"><a class="linkler" href="#">Kaydol</a></li>
        </nav>
        <div id="icerik">
        <form id="myForm" action="giris_bcknd.php" method="post"> 
        <span style="color:black">Kullanıcı Adı: </span>
            <input type="text" id= "ncknm" name="kullanici_adi"><br><br>
            <span style="color:black">Şifre:</span>
            <input type="password" id= "psw" name="sifre"><br><br>
            <button type="submit">Onayla</button>
            </form>  
            <div id="sonuc"></div> 
            <script >
                console.log("sa ab");
            </script>
        </div>
    </div>
    <script>
    document.getElementById("myForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        const formData = new FormData(this);
        fetch("giris_bcknd.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text()) 
        .then(data => {
            document.getElementById("sonuc").innerHTML = data;
            const scripts = document.getElementById("sonuc").getElementsByTagName("script");
            for (let script of scripts) {
                eval(script.textContent); 
            }
        })
        .catch(error => {
            console.error("Hata:", error);
        });
    });
</script>
    <script src="app.js"></script>
    <link rel="stylesheet" href="design.css">
</html>