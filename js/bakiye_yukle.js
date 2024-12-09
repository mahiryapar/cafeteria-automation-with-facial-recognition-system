

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('para_yukle_form');
    const kartNo = document.getElementById('kart_no');
    const skt = document.getElementById('skt');
    const cvv = document.getElementById('cvv');
    const bakiye = document.getElementById('bakiye');

    
    const onlyNumberInput = (input) => {
        input.addEventListener('keydown', (event) => {
            if (['e', 'E', '+', '-', '.'].includes(event.key)) {
                event.preventDefault();
            }
        });

        input.addEventListener('input', (event) => {
            input.value = input.value.replace(/\D/g, '');
        });
    };

    const validateForm = () => {
        let valid = true;
        if (!kartNo.value || !cvv || !skt.value || !bakiye.value) {
            alert('Lütfen tüm alanları doldurun.', "danger");
            valid = false;
            return;
        }
        if (kartNo.value.length !== 16) {
            alert('Kart numarası 16 haneli olmalıdır.', "danger");
            valid = false;
            return;
        }
        if (cvv.value.length !== 3) {
            alert('CVV numarası 3 haneli olmalıdır.', "danger");
            valid = false;
            return;
        }
        
        return valid;
    };

    onlyNumberInput(kartNo);
    onlyNumberInput(cvv);
    onlyNumberInput(bakiye);

    form.addEventListener('submit', function(event) {
        event.preventDefault(); 

        if (validateForm()) {
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
        }
    });
});

