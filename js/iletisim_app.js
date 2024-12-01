document.addEventListener("DOMContentLoaded", () => {
    const radioButtons = document.querySelectorAll("input[name='mesaj_tipi']");
    const gelen_mesajlar = document.getElementById("gelen_mesajlar");
    const giden_mesajlar = document.getElementById("giden_mesajlar");

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