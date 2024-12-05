var deger;

function deger_al(value){
    deger = value;
}

document.addEventListener("DOMContentLoaded", ()=>{
    if(deger == -1){
        document.getElementById('mesajlar_hakkinda_gozukuyor').style.display = 'block';    
    }
    else if(deger == -2){
        document.getElementById('ogrenci_gozukuyor').style.display = 'block';
    }
    else{
        document.getElementById('mesaj_gozukuyor').style.display = 'block';
    }

});



document.addEventListener("DOMContentLoaded", () => {
    const radioButtons = document.querySelectorAll("input[name='mesaj_tipi']");
    const gelen_mesajlar = document.getElementById("gelen_mesajlar");
    const giden_mesajlar = document.getElementById("giden_mesajlar");
    const uyeler_listesi = document.getElementById("uyeler_listesi");

    radioButtons.forEach((radio) => {
        radio.addEventListener("change", () => {
            if(radio.checked){
                if(radio.value == "gelen"){
                    uyeler_listesi.style.display = "none";
                    gelen_mesajlar.style.display = "block";
                    giden_mesajlar.style.display = "none";
                }
                else if(radio.value == "uyeler"){
                    uyeler_listesi.style.display = "block";
                    gelen_mesajlar.style.display = "none";
                    giden_mesajlar.style.display = "none";

                }
                else{
                    uyeler_listesi.style.display = "none";
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