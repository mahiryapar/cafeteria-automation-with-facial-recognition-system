const calendar = document.getElementById('calendar');
const today = new Date();
const firstDayOfWeek = today.getDay();
var deger,ogunler,alinan_menuler;
function deger_al(value,ogunler_value,alinan_menuler_value){
    deger = value;
    ogunler = ogunler_value;
    alinan_menuler = alinan_menuler_value;
}
document.addEventListener("DOMContentLoaded", ()=>{
    if(deger== 1){
        document.getElementById('warn').style.display = 'block';
    }
    else{
        document.querySelector('.container').style.display = 'block';
    }
    var ogunSecenekleri = document.querySelectorAll('.meal-option');
    var alinanMenuler = alinan_menuler;
    ogunSecenekleri.forEach(function(option) {
        var input = option.querySelector('input');
        var ogunAdi = input.value;
        var ogunTarihi = input.name.split('-')[1]+"-"+input.name.split('-')[2]+"-"+input.name.split('-')[3];
        var menuBilgisi = alinanMenuler.find(
                m => m.kategori === ogunAdi && m.tarih === ogunTarihi
        );
        
        if (menuBilgisi) {
                input.checked = true;
                input.disabled = true;
        }
        if (!ogunler.includes(ogunAdi)) {
            option.style.display = 'none';
        } 
});
});
document.getElementById("ogun_al").addEventListener("submit", function(event) {
    event.preventDefault(); 
    const formData = new FormData(this);
    fetch("../backend/ogunleri_al.php", {
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


for (let i = 1; i < (firstDayOfWeek === 0 ? 7 : firstDayOfWeek); i++) {
    const emptyDiv = document.createElement('div');
    emptyDiv.className = 'calendar-day empty';
    calendar.appendChild(emptyDiv);
}

for (let i = 0; i < 30; i++) {
    const day = new Date();
    day.setDate(today.getDate() + i);

    const dayElement = document.createElement('div');
    dayElement.className = 'calendar-day box';
    if (i === 0) dayElement.classList.add('today');
    dayElement.textContent = `${day.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long' })}\n${day.toLocaleDateString('tr-TR', {weekday: 'long'})}`;

    const optionsContainer = document.createElement('div');
    optionsContainer.className = 'meal-options';

    const breakfastOption = document.createElement('label');
    breakfastOption.className = 'meal-option';
    const breakfastCheckbox = document.createElement('input');
    breakfastCheckbox.type = 'checkbox';
    breakfastCheckbox.value = 'Kahvaltı';
    breakfastCheckbox.name = `breakfast-${day.toISOString().split('T')[0]}`;
    breakfastOption.appendChild(breakfastCheckbox);
    breakfastOption.appendChild(document.createTextNode(' Kahvaltı'));
    optionsContainer.appendChild(breakfastOption);

    const lunchOption = document.createElement('label');
    lunchOption.className = 'meal-option';
    const lunchCheckbox = document.createElement('input');
    lunchCheckbox.type = 'checkbox';
    lunchCheckbox.value = "Öğle Yemeği";
    lunchCheckbox.name = `lunch-${day.toISOString().split('T')[0]}`;
    lunchOption.appendChild(lunchCheckbox);
    lunchOption.appendChild(document.createTextNode(' Öğle Yemeği'));
    optionsContainer.appendChild(lunchOption);

    const dinnerOption = document.createElement('label');
    dinnerOption.className = 'meal-option';
    const dinnerCheckbox = document.createElement('input');
    dinnerCheckbox.type = 'checkbox';
    dinnerCheckbox.value = "Akşam Yemeği";
    dinnerCheckbox.name = `dinner-${day.toISOString().split('T')[0]}`;
    dinnerOption.appendChild(dinnerCheckbox);
    dinnerOption.appendChild(document.createTextNode(' Akşam Yemeği'));
    optionsContainer.appendChild(dinnerOption);

    dayElement.appendChild(optionsContainer);

    calendar.appendChild(dayElement);
}