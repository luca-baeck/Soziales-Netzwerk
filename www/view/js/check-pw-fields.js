const pw = document.getElementById("pw-first");
const pw_r = document.getElementById("pw-repeat");
var errorMessage;
window.addEventListener('load', function () {
    var errorMessages = document.getElementsByClassName('pw-invalid');
    errorMessage = errorMessages[0];
    errorMessage.style.display="none";
    errorMessage.innerText  = "";
    })

function check_pw(){
    var correct = false;
    if(pw.value != pw_r.value && pw_r.value != ""){
        errorMessage.style.display = "initial";
        errorMessage.innerText  = "entered passwords don't match...";
    }else{
        errorMessage.style.display = "none";
        errorMessage.innerText  = "";
        correct = true;
    }
    return correct;
}   


async function check_animate(){
    var pw_r_div = document.getElementById("repeat");
    var correct= check_pw();
    if(!correct){
        pw_r_div.classList.add("shake");
        setTimeout(function(){
            pw_r_div.classList.remove("shake");
        }, 500);
    }

}