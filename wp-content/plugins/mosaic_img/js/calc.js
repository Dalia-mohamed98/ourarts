"use strict";

function dragNdrop(event) {
    var fileName = URL.createObjectURL(event.target.files[0]);
    var preview = document.getElementById("preview");
    var previewImg = document.createElement("img");
    previewImg.setAttribute("src", fileName);
    preview.innerHTML = "";
    preview.appendChild(previewImg);
    // var processbtn = document.createElement("input");
    // processbtn.setAttribute("type", 'submit');
    // processbtn.setAttribute("value", "Proccess Image");
    // processbtn.setAttribute("name", "upload_mosaic_img");
    // processbtn.addClass("btn");
    // preview.appendChild(processbtn);
    



}
function drag() {
    document.getElementById('uploadFile').parentNode.className = 'draging dragBox';
}
function drop() {
    document.getElementById('uploadFile').parentNode.className = 'dragBox';
}

function processImg(){
    var wait = document.getElementById('wait');
    wait.innerHTML = "سيستغرق هذا بضع ثوانٍ ، يرجى الانتظار....";
    
}




