document.getElementById("para_yukle_form").addEventListener("submit", function(event) {
    event.preventDefault(); 
    const formData = new FormData(this);
    fetch("../backend/bakiye_yukle_backend.php", {
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