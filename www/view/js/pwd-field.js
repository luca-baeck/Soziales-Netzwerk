const inputs = document.querySelectorAll(".pwd input");
const eyes = document.querySelectorAll(".pwd .fa-eye-slash");
const locks = document.querySelectorAll(".pwd .fa-lock");

for (i=0; i<eyes.length; i++) {
    const eye = eyes[i];
    const input = inputs[i];
    const lock = locks[i];
    eye.addEventListener("click", () => {
        if(input.type === "password"){
            input.type = "text";
            eye.classList.remove("fa-eye-slash");
            eye.classList.add("fa-eye");
            lock.classList.remove("fa-lock");
            lock.classList.add("fa-unlock");
            
        }else{
            input.type = "password";
            eye.classList.add("fa-eye-slash");
            eye.classList.remove("fa-eye");
            lock.classList.add("fa-lock");
            lock.classList.remove("fa-unlock");
        
        }

    })
}


