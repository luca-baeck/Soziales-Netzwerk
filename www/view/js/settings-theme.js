const themes = document.querySelectorAll(".theme");

function select(index){
    if(!themes[index].classList.contains('active')){
        themes.forEach(function(node){
            node.classList.remove('active');
        });
        themes[index].classList.add('active');
        var theme = themes[index].getElementsByTagName('p')[0].innerHTML;
        localStorage.setItem('theme', theme);
        setTheme();
    }
    
}


if(localStorage.getItem('theme') != null){
    for(let i = 0; i<themes.length; i++){
        if(themes[i].getElementsByTagName('p')[0].innerHTML == localStorage.getItem('theme')){
            select(i);
            break;
        }
    }
}else{
    select(0);
}


