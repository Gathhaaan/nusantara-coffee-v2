// 1. DATA PETA (Edukasi)
const coffeeByRegion = {
  sumatra: { title: "Sumatra", items: ["Gayo (Aceh)", "Lintong (Sumut)", "Mandheling"] },
  java: { title: "Jawa", items: ["Java Preanger", "Ijen Raung", "Temanggung"] },
  kalimantan: { title: "Kalimantan", items: ["Liberika Kayong", "Sanggau"] },
  sulawesi: { title: "Sulawesi", items: ["Toraja", "Enrekang", "Kalosi"] },
  baliNtt: { title: "Bali & Nusa Tenggara", items: ["Bali Kintamani", "Flores Bajawa"] },
  maluku: { title: "Maluku", items: ["Maluku Islands Specialty"] },
  papua: { title: "Papua", items: ["Wamena", "Amungme"] },
};

// 2. SLIDESHOW HERO
function setupHeroSlideshow() {
  const container = document.getElementById("hero-slideshow");
  if (!container) return;
  
  const images = Array.from(container.querySelectorAll(".hero-background-image"));
  if (images.length < 2) return;

  let currentIndex = 0;
  setInterval(() => {
    images[currentIndex].classList.remove("active");
    currentIndex = (currentIndex + 1) % images.length;
    images[currentIndex].classList.add("active");
  }, 7000);
}

// 3. PETA INTERAKTIF
function setupMapTooltips() {
  const wrapper = document.querySelector(".map-wrapper");
  const tooltip = document.getElementById("tooltip");
  if (!wrapper || !tooltip) return;

  const show = (btn, regionKey) => {
    const data = coffeeByRegion[regionKey];
    if (!data) return;
    tooltip.innerHTML = `
      <h4 style="margin:0 0 8px 0; color:#4A3B32; border-bottom:1px solid #eee;">${data.title}</h4>
      <ul style="padding-left:1rem; margin:0; font-size:0.9rem;">${data.items.map(i => `<li>${i}</li>`).join("")}</ul>
      <div style="margin-top:5px; font-size:0.7rem; color:green; text-align:right;">Klik untuk cari 🔍</div>
    `;
    tooltip.hidden = false;
    
    // Posisi Tooltip
    const rect = btn.getBoundingClientRect();
    const parent = wrapper.getBoundingClientRect();
    const centerX = rect.left + rect.width/2 - parent.left;
    const topY = rect.top - parent.top;
    
    tooltip.style.left = `${centerX}px`;
    tooltip.style.top = `${topY - 10}px`;
    tooltip.style.transform = "translate(-50%, -100%)";
  };

  wrapper.querySelectorAll(".hotspot").forEach((btn) => {
    const key = btn.getAttribute("data-region");
    btn.addEventListener("mouseenter", () => show(btn, key));
    btn.addEventListener("mouseleave", () => tooltip.hidden = true);
    btn.addEventListener("click", () => {
        let keyword = key === 'baliNtt' ? 'Bali' : key;
        window.location.href = `marketplace.php?cari=${keyword}`;
    });
  });
}

// 4. FILTER SHOWCASE (Tab Kategori)
function setupShowcaseFilter() {
  const tabs = document.querySelectorAll(".filter-btn");
  const cards = document.querySelectorAll(".showcase-card");
  if (tabs.length === 0) return;

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
      
      const filter = tab.getAttribute("data-filter");
      cards.forEach(card => {
        if (filter === "all" || card.getAttribute("data-category") === filter) {
          card.classList.remove("hidden");
          card.style.opacity = "0";
          setTimeout(() => card.style.opacity = "1", 50);
        } else {
          card.classList.add("hidden");
        }
      });
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  setupHeroSlideshow();
  setupMapTooltips();
  setupShowcaseFilter();
});