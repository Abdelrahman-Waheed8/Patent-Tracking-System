document.addEventListener("DOMContentLoaded", () => {

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

  // ================= Deadlines Data =================
let deadlines = [
  {
    id: "US 18,123,456",
    title: "AI Monitoring System",
    date: "2026-04-10" 
  },
  {
    id: "EP 2 345 678",
    title: "Smart Packaging Device",
    date: "2026-04-30" 
  },
  {
    id: "US 17,234,567",
    title: "Solar Cooling System",
    date: "2026-06-20" 
  }
  
];

  // ================= Calculate Days Left =================
  function getDaysLeft(date) {
    let today = new Date();
    let deadline = new Date(date);

    let diff = deadline - today;
    return Math.ceil(diff / (1000 * 60 * 60 * 24));
  }

  // ================= Status Logic =================
  function getStatus(days) {
    if (days < 0) {
      return { class: "red", text: "Overdue", btn: "Pay" };
    } else if (days <= 10) {
      return { class: "orange", text: "Soon", btn: "Review" };
    } else {
      return { class: "blue", text: "Active", btn: "View" };
    }
  }

  // ================= Render Deadlines =================
  let container = document.querySelector(".deadline-list");

  if (!container) {
    console.error("deadline-list not found in HTML");
    return;
  }

  deadlines.forEach(item => {

    let days = getDaysLeft(item.date);
    let status = getStatus(days);

    let html = `
      <div class="deadline-item">
        <div>
          <h4>${item.id}</h4>
          <p>${item.title}</p>
        </div>

        <div class="right">
          <span class="status ${status.class}">
            ${status.text}
          </span>
          <button class="pay">${status.btn}</button>
        </div>
      </div>
    `;

    container.innerHTML += html;
  });

});