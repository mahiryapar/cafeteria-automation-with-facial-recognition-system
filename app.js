var alar = document.getElementsByTagName("a");
var linkler = document.querySelectorAll(".liler");
var nav = document.querySelector("#nav");
var icerikdivleri = document.getElementsByClassName("icerikdivleri");

// const squareDiv = document.querySelector('#prfl-foto');
  

// const width = squareDiv.offsetWidth;
// squareDiv.style.height = `${width}px`;





function runeventlist(){
    for(i = 0;i<linkler.length;i++){
        linkler[i].addEventListener("click",createClickListener(i));
        linkler[i].addEventListener("mousedown",transformab(i));
        linkler[i].addEventListener("mouseup",transformbittiab(i));
    }
    for(i= 0;i<icerikdivleri.length;i++){
        icerikdivleri[i].addEventListener("mouseenter",divacildi(i)); 
        icerikdivleri[i].addEventListener("mouseleave",divkuculdu(i)); 
    }
    nav.addEventListener("mouseenter",function(){
        nav.style.transition = "0.3s ease-in-out";
        nav.style.boxShadow = "0px 10px 15px 10px";
    })
    nav.addEventListener("mouseleave",function(){
        nav.style.boxShadow = "";
    })
}




function transformab(index){
    return function(){
        linkler[index].style.transform = "scale(0.85,0.85)";
        linkler[index].style.backgroundColor = "#222020";
    }
}

function transformbittiab(index){
    return function(){
        linkler[index].style.transform = "";
        linkler[index].style.backgroundColor = "";
    }
}


function createClickListener(index) {
    return function() {
        alar[index].click();
    };
}


runeventlist();
