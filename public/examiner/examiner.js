const modal = document.getElementById("modal");
const details = document.getElementById("review-details");

let currentId = null;

function openModal(btn) {
  const row = btn.closest("tr");

  currentId = row.dataset.id;
  document.getElementById("disc_id").value = currentId;

  const inventors = row.dataset.inventors
    ? row.dataset.inventors.split(",").map((i) => i.trim())
    : [];
  const files = row.dataset.files
    ? row.dataset.files.split(",").map((f) => f.trim())
    : [];
  const companies = row.dataset.companies
    ? row.dataset.companies.split(",").map((c) => c.trim())
    : [];

  const uploadBase = "../../src/";

  details.innerHTML = `
        <div class="detail-item">
            <strong>Title:</strong>
            <p>${row.dataset.title}</p>
        </div>

        <div class="detail-item">
            <strong>Application Number:</strong>
            <p>${row.dataset.appnum || "N/A"}</p>
        </div>

        <div class="detail-item">
            <strong>Filing Date:</strong>
            <p>${row.dataset.date}</p>
        </div>

        <div class="detail-item">
            <strong>Description:</strong>
            <p>${row.dataset.desc}</p>
        </div>

        <div class="detail-item">
            <strong>Status:</strong>
            <span class="badge pending">${row.dataset.status}</span>
        </div>

        <div class="detail-item">
            <strong>Inventors & Contributions:</strong>
            <ul>
                ${
                  inventors.length > 0 && inventors[0] !== ""
                    ? inventors.map((inv) => `<li>${inv}</li>`).join("")
                    : "<li>No inventors listed</li>"
                }
            </ul>
        </div>

        <div class="detail-item">
            <strong>External Companies:</strong>
            <p>${companies.length > 0 && companies[0] !== "" ? companies.join(" - ") : "No external agreements"}</p>
        </div>

        <div class="detail-item">
            <strong>Jurisdiction Information:</strong>
            <p>${row.dataset.jurisdictions || "Global/Not specified"}</p>
        </div>

        <div class="detail-item">
            <strong>Attached Documents:</strong>
            <div class="file-links">
                ${
                  files.length > 0 && files[0] !== ""
                    ? files
                        .map((path, index) => {
                          const cleanPath = path.trim();
                          const fullPath = uploadBase + cleanPath;

                          return `
                        <a href="${fullPath}" target="_blank" class="file-btn">
                            <i class="fas fa-file-pdf"></i> View PDF ${index + 1}
                        </a>`;
                        })
                        .join("")
                    : "<p>No files uploaded</p>"
                }
            </div>
        </div>
    `;

  modal.classList.add("active");
}

function closeModal() {
  modal.classList.remove("active");
}

function openRejectModal() {
  const rejectModal = document.getElementById("reject-modal");
  const rejectDiscId = document.getElementById("reject_disc_id");
  
  if (rejectModal && rejectDiscId) {
    rejectDiscId.value = currentId;
    rejectModal.classList.add("active");
    closeModal(); // Close the main review modal
  }
}

function closeRejectModal() {
  const rejectModal = document.getElementById("reject-modal");
  if (rejectModal) {
    rejectModal.classList.remove("active");
  }
}

function setStatus(status) {
  const statusInput = document.getElementById("status_input");
  if (statusInput) {
    statusInput.value = status;
  }
}
