const pw = document.getElementById("pw-first");
const pw_r = document.getElementById("pw-repeat");
var form = document.getElementById("form");
form.action = "";
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
        form.action = "";
    }else if(pw.value == pw_r.value && pw_r.value != ""){
        if(pw.value.length < 6){
            errorMessage.style.display = "initial";
            errorMessage.innerText  = "password must have at least 6 characters...";
            form.action = "";
        }else{
            errorMessage.style.display = "none";
            errorMessage.innerText  = "";
            correct = true;
            form.action = "/register/register";
        }
    }else{
        errorMessage.style.display = "none";
        errorMessage.innerText  = "";
        correct = true;
        form.action = "/register/register";
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