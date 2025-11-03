import UtilsCheck from '../utils/utils.js'

function checkDataError(username, mail, password) {
    let errorStr = "";

    if (!UtilsCheck.isValidUsername(username)) {
        errorStr = "Username must contains betwenn 3 and 10 characters"
        console.log("error")
        //return false;
    }
    if (!UtilsCheck.isValidEmail(mail)) {
        errorStr = "Mail invalide"
        //return false;
    }
    if (!UtilsCheck.isValidPassword(password)) {
        errorStr = "Password must contains minimum 8 characters, one letter, one number et one special character (!@#$%^&*()_\-+=)"
        //return false;
    }
    if (errorStr != "") {
        console.log(errorStr);
        return false
    }
    return true
}

function signupUser() {
    const username = UtilsCheck.sanitize(document.querySelector('input[name="username"]').value);
    const mail = UtilsCheck.sanitize(document.querySelector('input[name="email"]').value);
    const password = UtilsCheck.sanitize(document.querySelector('input[name="password"]').value);
    
    console.log("handle continue")
    console.log(username, mail, password)

    if (!checkDataError(username, mail, password)) {
        console.log("error data")
        return;
    }
    console.log("good data")

}

const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("password");
const eye = document.getElementById("eye");
const eyeOff = document.getElementById("eye-off");

togglePassword.addEventListener("click", () => {
    console.log("handle eye")
    const isVisible = passwordInput.type === "text";

    passwordInput.type = isVisible ? "password" : "text";

    eye.classList.toggle("hidden", !isVisible);
    eyeOff.classList.toggle("hidden", isVisible);
});

document.querySelectorAll("#signupForm input").forEach(input => {
    input.addEventListener("keydown", e => {
      if (e.key === "Enter") 
        signupUser();
      if (e.key === " " || e.code === "Space" || e.key === "Spacebar") {
        e.preventDefault();
      }
    });
});

document.getElementById("submit").addEventListener("click", () => {
    signupUser();
});