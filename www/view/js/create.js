
var currentSelectedID = null;
var currentSelectedElement = null;


var stickerInfo = [];


let root = document.documentElement;
var color1 = root.style.getPropertyValue('--logo-color1');
var color3 = root.style.getPropertyValue('--logo-color3');



function selectSticker(UUID, sticker){
    currentSelectedID = UUID;
    if(currentSelectedElement != null){
        currentSelectedElement.style.borderColor = color1;
        currentSelectedElement.style.borderWidth = "1px";
    }
    currentSelectedElement = sticker;
    currentSelectedElement.style.borderColor = color3;
    currentSelectedElement.style.borderWidth = "2px";
     
    var info = stickerInfo[currentSelectedID];

    
    var nameSticker = document.getElementById("name");
    var description = document.getElementById("description");
    var time = document.getElementById("time");
    var creator = document.getElementById("creator");
    var currentStickerImg = document.getElementById("currentSticker");
    
    nameSticker.innerText = "Name: " + info['Name'];
    description.innerText = "Description: " + info['Description'];
    time.innerText = "CreationTime: " + info['CreationTime'];
    
    currentStickerImg.src = currentSelectedElement.children[0].src;
    if(info['Handle'] != null){
        creator.innerText = "@" + info['Handle'];
    }else{
        creator.innerText = "";
    }

    var formValueSticker = document.getElementById("StickerID");
    formValueSticker.value = UUID;
}




window.addEventListener('load', function () {
    document.getElementsByClassName("sticker")[0].click();
})

