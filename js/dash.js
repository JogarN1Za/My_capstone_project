const navBar = document.querySelector("nav"),
  menuBtn = document.querySelector(".menu-icon"),
  overlay = document.createElement("div");

// Create overlay for mobile view
overlay.classList.add("overlay");
document.body.appendChild(overlay);

// Toggle sidebar on mobile
menuBtn.addEventListener("click", () => {
  if (window.innerWidth < 768) {
    navBar.classList.toggle("open");
  }
});

// Close sidebar when overlay is clicked (mobile only)
overlay.addEventListener("click", () => {
  navBar.classList.remove("open");
});

// Ensure sidebar is always open on desktop
window.addEventListener("resize", () => {
  if (window.innerWidth >= 768) {
    navBar.classList.remove("open");
  }
});
