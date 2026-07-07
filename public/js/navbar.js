import { postRequest } from "./request/requests.js";

const logoutBtn = document.getElementById("logoutBtn");

if (logoutBtn) {
    logoutBtn.addEventListener("click", async () => {
        const data = await postRequest("api/logout", {});
        if (!data) return;
        window.location.href = "/";
    });
}
