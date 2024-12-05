const giris_button = document.getElementById('signupbutton')
giris_button.addEventListener('click', kayit_kontrol)
let deger;

function php_degeral(value){
    deger = value;
    if(deger == 0){
        document.getElementById("kayit").style.display  = "block";
        document.getElementById("myFormsignup").addEventListener("submit", function(event) {
        event.preventDefault();
        if (!kayit_kontrol()) {
            return;
        }
        const formData = new FormData(this);
        fetch("../backend/giris_bcknd.php?giris=0", {
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
    }
    else{      
        document.getElementById("giris").style.display = "block";
        document.getElementById("myFormlogin").addEventListener("submit", function(event) {
        event.preventDefault(); 
        
        const formData = new FormData(this);
        fetch("../backend/giris_bcknd.php?giris=1", {
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
    }       

}

function kayit_kontrol()
{
    const name = document.getElementById("isim").value.trim();
    const surname = document.getElementById("soyisim").value.trim();
    const username = document.getElementById("ncknm").value.trim();
    const password = document.getElementById("psw").value.trim();
    const phone = document.getElementById("number").value.trim();
    const email = document.getElementById("mail").value.trim();
    if (!name || !surname || !username || !password || !phone || !email) {
        showAlert("Gerekli alanları doldurunuz.", "danger");
        return false;
    }
    const nameRegex = /^[a-zA-ZığüşöçİĞÜŞÖÇ]+$/;
    if (!nameRegex.test(name) || !nameRegex.test(surname)) {
        showAlert("İsim ve soyisim sadece harf içermelidir.", "danger");
        return false;
    }
    const usernameRegex = /^(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9_]+$/;
    if (!usernameRegex.test(username)) {
        showAlert("Kullanıcı adı en az bir büyük harf ve bir sayı içermelidir. Sadece '_' özel karakteri kullanılabilir.", "danger");
        return false;
    }
    if (!email.endsWith("@gmail.com")) {
        showAlert("E-posta '@gmail.com' formatında olmalıdır.", "danger");
        return false;
    }
    const phoneRegex = /^0\d{10}$/;
    if (!phoneRegex.test(phone)) {
        showAlert("Telefon numarası 11 haneli olmalı, 0 ile başlamalı ve sadece rakam içermelidir.", "danger");
        return false;
    }
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{6,}$/;
    if (!passwordRegex.test(password)) {
        showAlert("Şifre en az bir büyük harf ve bir sayı içermelidir ve en az 6 karakter olmalıdır.", "danger");
        return false;
    }


    return true
}
function showAlert(message, type = "success") {
    const alertDiv = document.getElementById("sonuc");
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `<strong>${type === "success" ? "Başarılı" : "Hata!"}</strong> ${message}`;
    alertDiv.style.display = "block";
    setTimeout(() => {
        alertDiv.style.display = "none";
    }, 5000);
}


