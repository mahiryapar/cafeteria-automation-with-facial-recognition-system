var deger;

function deger_al(value){
    deger = value;
}

document.addEventListener("DOMContentLoaded", () => {
    const radioButtons = document.querySelectorAll("input[name='mesaj_tipi']");
    const gelen_mesajlar = document.getElementById("gelen_mesajlar");
    const giden_mesajlar = document.getElementById("giden_mesajlar");
    if(deger == -1){
        document.getElementById('mesajlar_hakkinda_gozukuyor').style.display = 'block';    
    }
    else{
        document.getElementById('mesaj_gozukuyor').style.display = 'block';
    }
    radioButtons.forEach((radio) => {
        radio.addEventListener("change", () => {
            if(radio.checked){
                if(radio.value == "gelen"){
                    gelen_mesajlar.style.display = "block";
                    giden_mesajlar.style.display = "none";
                }
                else{
                    gelen_mesajlar.style.display = "none";
                    giden_mesajlar.style.display = "block";
                }
            }
        });
    });
});
document.getElementById("mesaj_gonder").addEventListener("submit", function(event) {
    event.preventDefault(); 
    const formData = new FormData(this);
    fetch("../backend/iletisim_backend.php", {
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