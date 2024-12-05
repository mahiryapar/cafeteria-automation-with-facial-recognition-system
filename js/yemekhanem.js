var yemekler;

function deger_al(yemekkategori){
    yemekler = yemekkategori;
}


document.addEventListener('DOMContentLoaded', function () {
    const ogunSelect = document.getElementById('ogun');
    ogunSelect.addEventListener('change', updateInputs);
});

function updateInputs() {
    const ogun = document.getElementById('ogun').value;
    const yemekSecenekleriDiv = document.getElementById('yemek_secenekleri');
    yemekSecenekleriDiv.innerHTML = ''; 
    if (ogun === 'Kahvaltı') {
        addDropdown('Ana Yemek', 'ana_yemek','Ana Yemek');
        addDropdown('Kahvaltılık 1', 'kahvaltilik_1', 'Ara Sıcak');
        addDropdown('Kahvaltılık 2', 'kahvaltilik_2', 'Ara Sıcak');
        addDropdown('Kahvaltılık 3', 'kahvaltilik_3', 'Ara Sıcak');
        addDropdown('İçecek', 'icecek', 'İçecek');
    } else {
        addDropdown('Ana Yemek', 'ana_yemek','Ana Yemek');
        addDropdown('Ara Sıcak', 'ara_sicak', 'Ara Sıcak');
        addDropdown('Çorba', 'corba', 'Çorba');
        addDropdown('Tatlı', 'tatli', 'Tatlı');
        addDropdown('İçecek', 'icecek', 'İçecek');
    }
}
function addDropdown(labelText, name, kategori = null) {
    const yemekSecenekleriDiv = document.getElementById('yemek_secenekleri');
    const label = document.createElement('label');
    label.textContent = labelText + ':';
    label.className = 'form-label mt-3';
    const select = document.createElement('select');
    select.name = name;
    select.id = name;
    select.className = 'form-control';
    select.required = true;
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.textContent = labelText + ' seçiniz';
    select.appendChild(defaultOption);
    if (kategori && yemekler[kategori]) {
        yemekler[kategori].forEach(yemek => {
            const option = document.createElement('option');
            option.value = yemek;
            option.textContent = yemek;
            select.appendChild(option);
        });
    } else if (!kategori) {
        Object.values(yemekler).flat().forEach(yemek => {
            const option = document.createElement('option');
            option.value = yemek;
            option.textContent = yemek;
            select.appendChild(option);
        });
    }
    yemekSecenekleriDiv.appendChild(label);
    yemekSecenekleriDiv.appendChild(select);
}