import UtilsCheck from '../utils/utils.js'
import { showToast } from "../toast.js";

function checkDataError(username, mail, password) {
    const rules = [
      {
        valid: UtilsCheck.isValidUsername(username),
        message: "Username must be 3–10 characters long.",
      },
      {
        valid: UtilsCheck.isValidEmail(mail),
        message: "Invalid email address",
      },
      {
        valid: UtilsCheck.isValidPassword(password),
        message: "Password must be at least 8 characters with a letter, number, and symbol",
      },
    ];
  
    const errors = rules.filter(rule => !rule.valid).map(rule => rule.message);
  
    if (errors.length > 0) {
      errors.forEach(msg => showToast(msg, "error"));
      return false;
    }
    return true;
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