// Ambil elemen
const sidebar = document.querySelector(".sidebar");
const mainContent = document.querySelector(".main-content");
const toggleBtn = document.getElementById("toggleSidebarHeader");

// Klik tombol ☰
toggleBtn?.addEventListener("click", (e) => {
  e.stopPropagation();

  if (window.innerWidth <= 768) {
    sidebar.classList.toggle("open");
  } else {
    sidebar.classList.toggle("closed");
    mainContent.classList.toggle("full");
  }
});

// Klik di luar sidebar (mobile) → tutup
document.addEventListener("click", (e) => {
  if (window.innerWidth <= 768 && sidebar.classList.contains("open")) {
    const isClickInside = sidebar.contains(e.target) || toggleBtn.contains(e.target);
    if (!isClickInside) sidebar.classList.remove("open");
  }
});

// Pastikan responsif pas resize
window.addEventListener("resize", () => {
  if (window.innerWidth > 768) sidebar.classList.remove("open");
});
