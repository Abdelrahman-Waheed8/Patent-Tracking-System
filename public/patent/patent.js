let patents = [
  { id: "PT-1", country: "USA", status: "Active", expiry: "2028-07-01", inventor: "John Doe", field: "Technology" },
  { id: "PT-2", country: "EU", status: "Expired", expiry: "2025-01-10", inventor: "Jane Smith", field: "Medical" },
  { id: "PT-3", country: "UK", status: "Abandoned", expiry: "2026-05-01", inventor: "Peter Jones", field: "Software" },
  { id: "PT-4", country: "Japan", status: "Active", expiry: "2030-11-20", inventor: "Yuki Tanaka", field: "Automotive" },
  { id: "PT-5", country: "Germany", status: "Active", expiry: "2029-03-15", inventor: "Klaus Mueller", field: "Engineering" },
];

// (Renewal Logic)
function getDaysLeft(date) {
  const today = new Date();
  const expiry = new Date(date);
  const diff = expiry - today;
  return Math.ceil(diff / (1000 * 60 * 60 * 24));
}
function getStatusClass(status) {
  return `status-badge status-${status.toLowerCase()}`;
}
//renwal color 
function getRenewalClass(days) {
  let text = `${days} days left`;
  if (days <= 0) text = "Expired";

  if (days > 90) return { class: "renew-safe", text: text };
  if (days > 30) return { class: "renew-medium", text: text };
  if (days > 0) return { class: "renew-danger", text: text };
  return { class: "renew-late", text: text };
}
function generateTable(filteredPatents) {
  const tableContainer = document.getElementById("patents-table");
  const data = filteredPatents || patents;

  let html = `
    <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Country</th>
          <th>Status</th>
          <th>Expiry Date</th>
          <th>Renewal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
  `;

  if (data.length === 0) {
    html += `<tr><td colspan="6" style="text-align:center; padding: 20px;">No patents found.</td></tr>`;
  } else {
    data.forEach(p => {
      const days = getDaysLeft(p.expiry);
      const renewal = getRenewalClass(days);

      html += `
        <tr data-id="${p.id}">
          <td><b>${p.id}</b></td>
          <td>${p.country}</td>
          <td><span class="${getStatusClass(p.status)}">${p.status}</span></td>
          <td>${p.expiry}</td>
          <td><span class="renewal-status ${renewal.class}">${renewal.text}</span></td>
          <td class="actions">
            <i class="fas fa-eye view-btn"></i>
            <i class="fas fa-dollar-sign payment-btn"></i>
          </td>
        </tr>
      `;
    });
  }

  html += `</tbody></table></div>`;
  tableContainer.innerHTML = html;

  // Add event listeners after table is generated
  addTableActionListeners();
}
//ـ Cards
function updateCards() {
  const total = patents.length;
  const active = patents.filter(p => p.status.toLowerCase() === "active").length;
  const expired = patents.filter(p => p.status.toLowerCase() === "expired").length;
  const abandoned = patents.filter(p => p.status.toLowerCase() === "abandoned").length;

  document.getElementById("total-patents").innerText = total;
  document.getElementById("active-patents").innerText = active;
  document.getElementById("expired-patents").innerText = expired;
  document.getElementById("abandoned-patents").innerText = abandoned;
}

// Search functionality
const searchInput = document.getElementById("search-input");
searchInput.addEventListener("input", (e) => {
  const searchTerm = e.target.value.toLowerCase();
  const filtered = patents.filter(p => 
    p.status.toLowerCase().includes(searchTerm) ||
    p.country.toLowerCase().includes(searchTerm) ||
    p.id.toLowerCase().includes(searchTerm)
  );
  generateTable(filtered);
});

// Modal Handling
const viewModal = document.getElementById("view-patent-modal");
const closeModalBtns = document.querySelectorAll(".close-modal");

function showModal(modal) {
  modal.classList.add("active");
}

function hideModals() {
  document.querySelectorAll(".modal-overlay").forEach(modal => {
    modal.classList.remove("active");
  });
}

closeModalBtns.forEach(btn => {
  btn.addEventListener("click", hideModals);
});

function addTableActionListeners() {
    document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", (e) => {
            const patentId = e.target.closest("tr").dataset.id;
            const patent = patents.find(p => p.id === patentId);
            if (patent) {
                const detailsContainer = document.getElementById("view-patent-details");
                detailsContainer.innerHTML = `
                    <div class="detail-item"><strong>ID:</strong> <span>${patent.id}</span></div>
                    <div class="detail-item"><strong>Country:</strong> <span>${patent.country}</span></div>
                    <div class="detail-item"><strong>Status:</strong> <span class="${getStatusClass(patent.status)}">${patent.status}</span></div>
                    <div class="detail-item"><strong>Expiry Date:</strong> <span>${patent.expiry}</span></div>
                    <div class="detail-item"><strong>Inventor:</strong> <span>${patent.inventor}</span></div>
                    <div class="detail-item"><strong>Field of Invention:</strong> <span>${patent.field}</span></div>
                `;
                showModal(viewModal);
            }
        });
    });

    document.querySelectorAll(".payment-btn").forEach(btn => {
        btn.addEventListener("click", (e) => {
            const patentId = e.target.closest("tr").dataset.id;
            const patent = patents.find(p => p.id === patentId);

            if (patent) {
                let title, text, icon;
                switch (patent.status.toLowerCase()) {
                    case "active":
                        title = "Patent Active!";
                        text = `Patent ${patent.id} is currently Active. Your rights are protected.`;
                        icon = "success";
                        break;
                    case "expired":
                        title = "Patent Expired!";
                        text = `Warning! Patent ${patent.id} has Expired. Take immediate action to renew if possible.`;
                        icon = "warning";
                        break;
                    case "abandoned":
                        title = "Patent Abandoned!";
                        text = `Notice: Patent ${patent.id} is Abandoned. You no longer hold rights to this invention.`;
                        icon = "error";
                        break;
                    default:
                        title = "Payment Information";
                        text = `Payment information for Patent ID: ${patent.id}.`;
                        icon = "info";
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    draggable: true
                });
            }
        });
    });
}

function toggleSidebar() {
    document.querySelector('.container').classList.toggle('closed');
}

generateTable();
updateCards();