document.querySelectorAll("#loginForm input").forEach(input => {
    input.addEventListener("keyup", e => {
      if (e.key === "Enter") loginUser();
    });
});

document.getElementById("submit").addEventListener("click", () => {
    loginUser();
});

function loginUser() {
    console.log("handle continue")
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