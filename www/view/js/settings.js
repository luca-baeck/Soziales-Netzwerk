const tabBtn = document.querySelectorAll(".tab");
const tab = document.querySelectorAll(".tabShow");
var $_GET=[];
window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(a,name,value){$_GET[name]=value;});

function tabs(index){
    tab.forEach(function(node){
        node.style.display = "none";
    });
    tab[index].style.display = "block";
    tabBtn.forEach(function(node){
        node.classList.remove("active");
    });
    tabBtn[index].classList.add("active");
    window.history.replaceState(null, null, ("?tab=" + index));
}



if($_GET['tab'] == null){
    tabs(0);
}else{
    tabs(parseInt($_GET['tab']));
}


