
var currentSelectedID = null;
var currentSelectedElement = null;
var formValueSticker = document.getElementById("StickerID");
var stickers = document.getElementsByClassName("Sticker");
var stickerInfo = [];
let root = document.documentElement;
var color1 = root.style.getPropertyValue('--logo-color1');
var color3 = root.style.getPropertyValue('--logo-color3');

function selectSticker(UUID, sticker){
    currentSelectedID = UUID;
    if(currentSelectedElement != null){
        currentSelectedElement.style.borderColor = color1;
    }
    currentSelectedElement = sticker;
    currentSelectedElement.style.borderColor = color3;
    console.log(stickerInfo[0]);
    console.log(stickers[0]);
}


