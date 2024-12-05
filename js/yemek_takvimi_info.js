var deger,kahv,ogle,aksam;

function deger_al(value,ka,og,ak){
    deger = value;
    kahv = ka;
    ogle = og;
    aksam = ak;
}

document.getElementById("kahvalti_yorum_yaz").addEventListener("submit", function(event) {
    event.preventDefault(); 
    const formData = new FormData(this);
    fetch("../backend/yorum_ekle.php", {
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
document.getElementById("ogle_yorum_yaz").addEventListener("submit", function(event) {
    event.preventDefault(); 
    const formData = new FormData(this);
    fetch("../backend/yorum_ekle.php", {
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
document.getElementById("aksam_yorum_yaz").addEventListener("submit", function(event) {
    event.preventDefault(); 
    const formData = new FormData(this);
    fetch("../backend/yorum_ekle.php", {
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


document.addEventListener("DOMContentLoaded", ()=>{
    if(deger == 1){
        document.getElementById('warn').style.display = 'block';
    }
    if(kahv == 1){
        document.getElementById('kahvalti').style.display = 'block';
    }
    if(ogle == 1){
        document.getElementById('ogle').style.display = 'block';
    }
    if(aksam == 1){
        document.getElementById('aksam').style.display = 'block';
    }
});