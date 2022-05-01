vid_fullres = document.getElementById("vid_fullres");
vid_fullres.style.display="none";
vid_fullres.addEventListener('loadeddata', function() {
  loadedFullRes();
}, false);
vid_fullres.load();


vid_lowres = document.getElementById("vid_lowres")
vid_lowres.style.display="none";
vid_lowres.addEventListener('loadeddata', function() {
  loadedLowRes();
}, false);
vid_lowres.load();


pic = document.getElementById("pic")
pic.style.display="initial";

function loadedLowRes(){
    vid_fullres.style.display="none";
    pic.style.display="none";
    vid_lowres.style.display="initial";
}

function loadedFullRes(){
    time = vid_lowres.currentTime;
    vid_fullres.style.display="initial";
    pic.style.display="none";
    vid_lowres.style.display="none";
    vid_fullres.currentTime = time;
}

