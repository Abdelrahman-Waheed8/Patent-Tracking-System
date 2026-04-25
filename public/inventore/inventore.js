
  // ================= Sidebar Toggle =================
  function toggleSidebar() {
    document.querySelector('.container').classList.toggle('closed');
  }

  window.toggleSidebar = toggleSidebar;

  // ================= Active Menu =================
  let links = document.querySelectorAll(".menu a");

  links.forEach(link => {
    link.addEventListener("click", function () {
      links.forEach(l => l.classList.remove("active"));
      this.classList.add("active");
    });
  });
