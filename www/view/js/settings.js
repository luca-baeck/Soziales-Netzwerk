const tabBtn = document.querySelectorAll(".tab");
const tab = document.querySelectorAll(".tabShow");

function tabs(index){
    tab.forEach(function(node){
        node.style.display = "none";
    });
    tab[index].style.display = "block";
    tabBtn.forEach(function(node){
        node.classList.remove("active");
    });
    tabBtn[index].classList.add("active");
}
tabs(0);

