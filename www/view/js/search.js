var $_GET=[];
window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(a,name,value){$_GET[name]=value;});


var elements = document.getElementsByClassName("selectI");

var tabsElem = document.getElementsByClassName("tabShow");

var currentSelectedIndex = 0;

let root = document.documentElement;
var color1 = root.style.getPropertyValue('--logo-color1');
var color3 = root.style.getPropertyValue('--logo-color3');

function select(index){
    elements[currentSelectedIndex].style.color = color3;
    tabsElem[currentSelectedIndex].style.display = "none";
    currentSelectedIndex = index;
    elements[currentSelectedIndex].style.color = color1; 
    tabsElem[currentSelectedIndex].style.display = "inline";
    window.history.replaceState(null, null, ("?tab=" + currentSelectedIndex));
}

if($_GET['tab'] == null){
    select(0);
}else{
    select(parseInt($_GET['tab']));
}