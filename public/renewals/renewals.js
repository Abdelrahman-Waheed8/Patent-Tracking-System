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
    document.querySelectorAll(".review-btn").forEach(btn => {
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

addTableActionListeners();