const examinerData = [
    {
        id: "INV-2026-001",
        title: "AI-based Monitoring System",
        inventor: "John Doe",
        status: "Pending",
        description: "Advanced smart packaging with sensors.",
        references: "Packaging Research 2024",
        files: "packaging_device.pdf"
    },
    {
        id: "INV-2026-002",
        title: "Smart Packaging Device",
        inventor: "Jane Smith",
        status: "Pending",        description: "Advanced smart packaging with sensors.",
        references: "Packaging Research 2024",
        files: "packaging_device.pdf"
    },
    {
        id: "INV-2026-003",
        title: "Smart Packaging Device",
        inventor: "Jane Smith",
        status: "rejected",
        description: "Advanced smart packaging with sensors.",
        references: "Packaging Research 2024",
        files: "packaging_device.pdf"
    }
];

document.addEventListener("DOMContentLoaded", () => {


let table = document.getElementById("table_body");

examinerData.forEach(item => {
    table.innerHTML += `
        <tr data-id="${item.id}">
            <td>
                <strong>${item.title}</strong><br>
                <small>${item.id}</small>
            </td>
            <td>${item.inventor}</td>
            <td>
                <span class="status-badge ${getStatusClass(item.status)}">
                    ${item.status}
                </span>
            </td>
            <td>
                <button class="review-btn">Review</button>
            </td>
        </tr>
    `;
});
function getStatusClass(status) {
    switch(status.toLowerCase()) {
        case "pending":
            return "status-pending";
        case "approved":
            return "status-approved";
        case "rejected":
            return "status-rejected";
        default:
            return "status-pending";
    }
}
    const modal = document.getElementById("review-modal");
    const closeBtn = document.querySelector(".close-modal");
    const detailsContainer = document.getElementById("review-details");
    let currentRow = null;
    // Review Button
document.addEventListener("click", (e) => {
    if (e.target.classList.contains("review-btn")) {
        const row = e.target.closest("tr");
        currentRow = row;
        const id = row.dataset.id;
        const patent = examinerData.find(p => p.id === id);

        detailsContainer.innerHTML = `
            <div class="detail-item">
                <strong>Title:</strong>
                <span>${patent.title}</span>
            </div>
            <div class="detail-item">
                <strong>Inventor:</strong>
                <span>${patent.inventor}</span>
            </div>
            <div class="detail-item">
                <strong>Description:</strong>
                <p>${patent.description}</p>
            </div>
            <div class="detail-item">
                <strong>References:</strong>
                <span>${patent.references}</span>
            </div>
            <div class="detail-item">
                <strong>Files:</strong>
                <span>${patent.files}</span>
            </div>
            <div class="decision-buttons">
                    <button class="approve-btn">
                        Approve
                    </button>
                    <button class="reject-btn">
                        Reject
                    </button>
                    <button class="revision-btn">
                        Need Revision
                    </button>
                </div>
        `;

        modal.classList.add("active");
    }
if (
    e.target.classList.contains("approve-btn") ||
    e.target.classList.contains("reject-btn") ||
    e.target.classList.contains("revision-btn")
) {

    const id = currentRow.dataset.id;
    const patent = examinerData.find(p => p.id === id);
    let newStatus = patent.status;
    if (e.target.classList.contains("approve-btn")) {
        newStatus = "approved";
    } 
    else if (e.target.classList.contains("reject-btn")) {
        newStatus = "rejected";
    } 
    else if (e.target.classList.contains("revision-btn")) {
        newStatus = "Pending"; 
    }
    patent.status = newStatus;
    const statusCell = currentRow.querySelector(".status-badge");
    statusCell.textContent = newStatus;
    statusCell.className = `status-badge ${getStatusClass(newStatus)}`;
    modal.classList.remove("active");
}
});
    // Close Modal
    closeBtn.addEventListener("click", () => {
        modal.classList.remove("active");
    });
    // Close when clicking outside
    modal.addEventListener("click", (e) => {
        if(e.target === modal) {
            modal.classList.remove("active");
        }
    });

});