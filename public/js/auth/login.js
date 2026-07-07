import UtilsCheck from '../utils/utils.js'
import { showToast } from "../toast.js";
import { postRequest } from "../request/requests.js";

const verified = new URLSearchParams(window.location.search).get("verified");
if (verified === "1") {
    showToast("Account confirmed! You can now log in.", "success");
} else if (verified === "0") {
    showToast("This confirmation link is invalid or has expired.", "error");
}
if (verified !== null) {
    window.history.replaceState({}, document.title, "/login");
}

document.querySelectorAll("#loginForm input").forEach(input => {
    input.addEventListener("keyup", e => {
      if (e.key === "Enter") loginUser();
    });
});

document.getElementById("submit").addEventListener("click", () => {
    loginUser();
});

async function loginUser() {
    const username = UtilsCheck.sanitize(document.querySelector('input[name="username"]').value);
    const password = UtilsCheck.sanitize(document.querySelector('input[name="password"]').value);

    if (!username || !password) {
        showToast("Username and password are required", "error");
        return;
    }

    const data = await postRequest("api/login", { username, password });

    if (!data) return;

    showToast("Logged in successfully", "success");
    window.location.href = "/";
}

const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("password");
const eye = document.getElementById("eye");
const eyeOff = document.getElementById("eye-off");

togglePassword.addEventListener("click", () => {
    const isVisible = passwordInput.type === "text";

    passwordInput.type = isVisible ? "password" : "text";

    eye.classList.toggle("hidden", !isVisible);
    eyeOff.classList.toggle("hidden", isVisible);
});
