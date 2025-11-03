export function showToast(message, type = "error") {
  const container = document.getElementById("toastContainer");
  if (!container) return;

  const toast = document.createElement("div");
  toast.className = `
    toast-enter 
    flex flex-row items-center w-80 items-center gap-3 
    px-4 py-4 rounded-lg shadow-lg text-[--color-black] font-medium bg-[--color-toast]
  `;

  const icon =
    type === "success"
      ? `<svg class="h-10 w-10 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M20 7L9.00004 18L3.99994 13" stroke="#6a994e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>`
      : `<svg class="h-10 w-10 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 16.99V17M12 7V14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#941b0c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>`;

  toast.innerHTML = `
    <div class="flex items-center gap-2">
      ${icon}
      <span>${message}</span>
    </div>
    <button class="absolute top-1 right-2 text-white/80 hover:text-white font-bold text-lg leading-none">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M19 5L4.99998 19M5.00001 5L19 19" stroke="#432818" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
    </button>
  `;

  toast.querySelector("button").addEventListener("click", () => toast.remove());

  container.appendChild(toast);

  setTimeout(() => {
    toast.classList.remove("toast-enter");
    toast.classList.add("toast-leave");
    toast.addEventListener("animationend", () => toast.remove());
  }, 5000);
}

