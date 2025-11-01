// 🖼️ Quelques images à afficher
const images = [
  "https://images.unsplash.com/photo-1760440948623-9c5343a753a7?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1760379682992-addb38e0f6c8?auto=format&fit=crop&w=800&q=80",
  "https://plus.unsplash.com/premium_photo-1761692476571-5fc8b3f6ae24?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1743961117237-a65e1abcab84?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1496449903678-68ddcb189a24?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1504203700686-0b5b26a0c6b7?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1606788075761-7ab58c2a9b33?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1514516870926-206a37c74c21?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1499084732479-de2c02d45fc4?auto=format&fit=crop&w=800&q=80",
  "https://images.unsplash.com/photo-1761872936306-cede97eb846d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1364",
  "https://plus.unsplash.com/premium_photo-1753982281845-39e087348555?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=2340",
  "https://images.unsplash.com/photo-1761777939781-2fb1b636d5fc?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1285",
  "https://images.unsplash.com/photo-1755446133347-d9def00b03a3?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=3640",
  "https://images.unsplash.com/photo-1761839257789-20147513121a?ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=2338",
  "https://images.unsplash.com/photo-1759083696193-40be3c5f4ff2?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1287",
  "https://images.unsplash.com/photo-1761880750277-b49d02ab7480?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=2340",
  "https://images.unsplash.com/photo-1761872789483-13f597d18b76?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1364",


];

const masonry = document.getElementById("masonry");

// ⚙️ Nombre de colonnes selon la taille d’écran
const getColumnsCount = () => {
  if (window.innerWidth >= 1024) return 4; // lg
  if (window.innerWidth >= 768) return 3;  // md
  if (window.innerWidth >= 640) return 2;  // sm
  return 1;                               // mobile
};

// 🧮 Fonction Masonry
function createMasonry() {
  masonry.innerHTML = ""; // Reset
  const colsCount = getColumnsCount();
  const colHeights = Array(colsCount).fill(0);
  const colElements = [];

  // Crée les colonnes dynamiquement
  for (let i = 0; i < colsCount; i++) {
    const col = document.createElement("div");
    col.className = "flex flex-col gap-4 flex-1";
    masonry.appendChild(col);
    colElements.push(col);
  }

  // Charge les images une par une pour obtenir leur hauteur
  images.forEach(src => {
    const imgWrapper = document.createElement("div");
    imgWrapper.className =
      "relative overflow-hidden rounded-xl shadow-md transition-transform duration-300 hover:scale-95 hover:brightness-110 cursor-pointer";
    
    const img = document.createElement("img");
    img.src = src;
    img.className = "w-full h-auto object-cover block";

    img.onload = () => {
      // Trouve la colonne la plus courte
      const shortest = colHeights.indexOf(Math.min(...colHeights));
      colElements[shortest].appendChild(imgWrapper);
      imgWrapper.appendChild(img);
      colHeights[shortest] += img.height + 16; // 16px = gap approx
    };
  });
}

// 🪄 Initialisation
window.addEventListener("load", createMasonry);
window.addEventListener("resize", () => {
  clearTimeout(window._masonryResizeTimer);
  window._masonryResizeTimer = setTimeout(createMasonry, 300);
});