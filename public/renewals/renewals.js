document.addEventListener("DOMContentLoaded", function () {
    const viewModal = document.getElementById("view-patent-modal");
    const closeModalBtns = document.querySelectorAll(".close-modal");
    const renewalsTable = document.querySelector("#renewals-table tbody");
    const payBtn = document.getElementById("pay-btn");
    const transactionContainer = document.getElementById("transaction-container");
    const transactionInput = document.getElementById("transaction-input");
    const hiddenPatentIdInput = document.getElementById("form-patent-id");

    function showModal(modal) {
        modal.classList.add("active");
    }

    function hideModals() {
        document.querySelectorAll(".modal-overlay").forEach(modal => {
            modal.classList.remove("active");
        });
        transactionInput.value = ""; // Clear input on close
    }

    closeModalBtns.forEach(btn => {
        btn.addEventListener("click", hideModals);
    });

    viewModal.addEventListener("click", function (e) {
        if (e.target === viewModal) {
            hideModals();
        }
    });

    if (renewalsTable) {
        renewalsTable.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains("review-btn")) {
                const row = e.target.closest("tr");
                const cells = row.querySelectorAll("td");

                const patentId = cells[0].textContent;

                document.getElementById("modal-patent-id").textContent = patentId;
                document.getElementById("modal-patent-title").textContent = cells[1].textContent;
                document.getElementById("modal-grant-date").textContent = cells[2].textContent;
                document.getElementById("modal-status").textContent = cells[3].textContent;
                document.getElementById("modal-deadline").textContent = cells[4].textContent;
                document.getElementById("modal-due-date").textContent = cells[5].textContent;
                document.getElementById("modal-days-left").textContent = cells[6].textContent;

                const fee = cells[7].textContent.trim();
                document.getElementById("modal-estimated-fee").textContent = fee;

                // Set the hidden input value for the form submission
                hiddenPatentIdInput.value = patentId;

                if (fee === "N/A") {
                    payBtn.disabled = true;
                    payBtn.style.opacity = "0.5";
                    payBtn.style.cursor = "not-allowed";
                    transactionContainer.style.display = "none";
                    transactionInput.removeAttribute("required");
                } else {
                    payBtn.disabled = false;
                    payBtn.style.opacity = "1";
                    payBtn.style.cursor = "pointer";
                    transactionContainer.style.display = "flex"; // Show input field
                    transactionInput.setAttribute("required", "required");
                }

                showModal(viewModal);
            }
        });
    }
});