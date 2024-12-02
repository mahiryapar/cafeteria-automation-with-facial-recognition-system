var alar = document.getElementsByTagName("a");
var linkler = document.querySelectorAll(".liler");
var nav = document.querySelector("#nav");
var signlar = document.querySelectorAll("#signin");
var adminliler = document.querySelectorAll("#admin-li");
var ogrencililer = document.querySelectorAll("#ogrenci-li");

document.addEventListener("DOMContentLoaded", INIT);


function INIT(){
    document.querySelectorAll('a').forEach(a => {
        a.addEventListener('dragstart', (e) => {
            e.preventDefault(); 
        });
    });
    fetch('../backend/session_data.php')
    .then(response => response.json())
    .then(data => {
    if (data.role == "admin") {
        for (let i = 0; i < signlar.length; i++) {
            signlar[i].style.display = "none";
        }
        for (let i = 0; i < adminliler.length; i++) {
            adminliler[i].style.display = "block";
        }
        for (let i = 0; i < ogrencililer.length; i++) {
            ogrencililer[i].style.display = "none";
        }
        document.getElementsByClassName("prfl")[0].style.display = "flex";

    }
    else if(data.role == "ogrenci"){
        for (let i = 0; i < signlar.length; i++) {
            signlar[i].style.display = "none";
        }
        for (let i = 0; i < adminliler.length; i++) {
            adminliler[i].style.display = "none";
        }
        for (let i = 0; i < ogrencililer.length; i++) {
            ogrencililer[i].style.display = "block";
        }
        document.getElementsByClassName("prfl")[0].style.display = "flex";
    }
    else{
        for (let i = 0; i < signlar.length; i++) {
            signlar[i].style.display = "block";
        }
        for (let i = 0; i < adminliler.length; i++) {
            adminliler[i].style.display = "none";
        }
        for (let i = 0; i < ogrencililer.length; i++) {
            ogrencililer[i].style.display = "none";
        }
        document.getElementsByClassName("prfl")[0].style.display = "none";
    }
    })
    .catch(error => console.error('Hata:', error));
}








function runeventlist() {
    for (i = 0; i < linkler.length; i++) {
        linkler[i].addEventListener("click", createClickListener(i));
        linkler[i].addEventListener("mousedown", transformab(i));
        linkler[i].addEventListener("mouseup", transformbittiab(i));
    }
    nav.addEventListener("mouseenter", function () {
        nav.style.transition = "0.3s ease-in-out";
        nav.style.boxShadow = "0px 10px 15px 10px";
    })
    nav.addEventListener("mouseleave", function () {
        nav.style.boxShadow = "";
    })
}




function transformab(index) {
    return function () {
        linkler[index].style.transform = "scale(0.85,0.85)";
        linkler[index].style.backgroundColor = "#222020";
    }
}

function transformbittiab(index) {
    return function () {
        linkler[index].style.transform = "";
        linkler[index].style.backgroundColor = "";
    }
}


function createClickListener(index) {
    return function () {
        alar[index].click();
    };
}


runeventlist();
