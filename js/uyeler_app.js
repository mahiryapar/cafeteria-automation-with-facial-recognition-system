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