
const modal = document.getElementById("modal");
const details = document.getElementById("review-details");

let currentId = null;

// فتح المودال
function openModal(btn) {

    const row = btn.closest("tr");

    currentId = row.dataset.id;
    document.getElementById("disc_id").value = currentId;

    details.innerHTML = `
        <div class="detail-item">
            <strong>Title:</strong>
            <p>${row.dataset.title}</p>
        </div>

        <div class="detail-item">
            <strong>Application Number:</strong>
            <p>${row.dataset.appnum}</p>
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
            <p>${row.dataset.status}</p>
        </div>
    `;

    modal.classList.add("active");
}

// غلق المودال
function closeModal() {
    modal.classList.remove("active");
}

// تحديد الحالة قبل الإرسال
function setStatus(status) {
    document.getElementById("status_input").value = status;
}