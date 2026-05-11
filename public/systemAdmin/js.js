// ================== Users Management ==================
let users = [
  {
    firstName: "Admin",
    lastName: "Admin",
    email: "admin@example.com",
    role: "IP Counsel",
  },
  {
    firstName: "hoba",
    lastName: "",
    email: "admin@example.com",
    role: "Examiner",
  },
];

let currentUserIndex = null; // To track which user is being edited

function addUser() {
  let firstName = document.getElementById("first_name").value;
  let lastName = document.getElementById("last_name").value;
  let email = document.getElementById("email").value;
  let role = document.getElementById("role").value;

  let user = {
    firstName: firstName,
    lastName: lastName,
    email: email,
    role: role,
  };

  users.push(user);
  renderUsers();

  // Clear form fields
  document.getElementById("first_name").value = "";
  document.getElementById("last_name").value = "";
  document.getElementById("email").value = "";
  document.getElementById("role").value = "";
}

function renderUsers() {
  let table = document.getElementById("usersTable");
  table.innerHTML = "";

  users.forEach((u, index) => {
    table.innerHTML += `
            <tr>
                <td>${u.firstName}</td>
                <td>${u.lastName}</td>
                <td>${u.email}</td>
                <td>${u.role}</td>
                <td>
                    <i class="fas fa-edit icon-btn icon-edit" onclick="editUser(${index})"></i>
                    <i class="fas fa-trash icon-btn icon-delete" onclick="deleteUser(${index})"></i>
                </td>
            </tr>
        `;
  });
}

function deleteUser(index) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#296d74",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      users.splice(index, 1);
      renderUsers();
      Swal.fire("Deleted!", "The user has been deleted.", "success");
    }
  });
}

function editUser(index) {
  currentUserIndex = index;
  const user = users[index];

  document.getElementById("edit_first_name").value = user.firstName;
  document.getElementById("edit_last_name").value = user.lastName;
  document.getElementById("edit_email").value = user.email;
  document.getElementById("edit_role").value = user.role;

  document.getElementById("editModal").style.display = "flex";
  document.getElementById("editModalOverlay").style.display = "block";
  document.querySelector(".container").classList.add("blurred");
}

function closeModal() {
  document.getElementById("editModal").style.display = "none";
  document.getElementById("editModalOverlay").style.display = "none";
  document.querySelector(".container").classList.remove("blurred");
}

function saveChanges() {
  if (currentUserIndex === null) return;

  const updatedUser = {
    firstName: document.getElementById("edit_first_name").value,
    lastName: document.getElementById("edit_last_name").value,
    email: document.getElementById("edit_email").value,
    role: document.getElementById("edit_role").value,
  };

  users[currentUserIndex] = updatedUser;
  renderUsers();
  closeModal();
}

// ================== License Requests Management ==================
let licenseRequests = [
  {
    id: "LR-001",
    applicant: "Ahmed Ali",
    licenseType: "Patent",
    status: "Pending",
  },
  {
    id: "LR-002",
    applicant: "Sara Khaled",
    licenseType: "Trademark",
    status: "Pending",
  },
  {
    id: "LR-003",
    applicant: "Omar Hassan",
    licenseType: "Copyright",
    status: "Pending",
  },
];

function renderLicenseRequests() {
  let table = document.getElementById("licenseRequestsTable");
  table.innerHTML = "";

  licenseRequests.forEach((req, index) => {
    let statusClass = "";
    switch (req.status) {
      case "Accepted":
        statusClass = "status-accepted";
        break;
      case "Rejected":
        statusClass = "status-rejected";
        break;
      default:
        statusClass = "status-pending";
    }

    table.innerHTML += `
            <tr>
                <td>${req.id}</td>
                <td>${req.applicant}</td>
                <td>${req.licenseType}</td>
                <td><span class="${statusClass}">${req.status}</span></td>
                <td>
                    ${
                      req.status === "Pending"
                        ? `
                        <button class="btn-accept" onclick="acceptRequest(${index})">Accept</button>
                        <button class="btn-reject" onclick="rejectRequest(${index})">Reject</button>
                    `
                        : ""
                    }
                    <i class="fas fa-trash icon-btn icon-delete" onclick="deleteRequest(${index})"></i>
                </td>
            </tr>
        `;
  });
}

function acceptRequest(index) {
  Swal.fire({
    title: "Accept Request?",
    text: "This will mark the request as accepted.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#296d74",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, accept it!",
  }).then((result) => {
    if (result.isConfirmed) {
      licenseRequests[index].status = "Accepted";
      renderLicenseRequests();
      Swal.fire("Accepted!", "The request has been accepted.", "success");
    }
  });
}

function rejectRequest(index) {
  Swal.fire({
    title: "Reject Request?",
    text: "This will mark the request as rejected.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#296d74",
    confirmButtonText: "Yes, reject it!",
  }).then((result) => {
    if (result.isConfirmed) {
      licenseRequests[index].status = "Rejected";
      renderLicenseRequests();
      Swal.fire("Rejected!", "The request has been rejected.", "info");
    }
  });
}

function deleteRequest(index) {
  Swal.fire({
    title: "Delete Request?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#296d74",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      licenseRequests.splice(index, 1);
      renderLicenseRequests();
      Swal.fire("Deleted!", "The request has been deleted.", "success");
    }
  });
}

// ================== Initial Render ==================
document.addEventListener("DOMContentLoaded", function () {
  renderUsers();
  renderLicenseRequests();
});

// ================== Sidebar Toggle (placeholder) ==================
function toggleSidebar() {
  // Implement sidebar toggle logic if needed
  console.log("Sidebar toggle");
}

async function adminPostAction(action, id) {
  const formData = new FormData();
  formData.append("action", action);
  formData.append("id", id);

  const response = await fetch("../../src/licensing.php", {
    method: "POST",
    body: formData,
  });

  const payload = await response.json();
  if (!response.ok || !payload.success) {
    throw new Error(payload.message || "Request failed");
  }
  return payload;
}

function adminApproveLicense(id) {
  Swal.fire({
    title: "Approve license?",
    text: "This will approve the selected license request.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#296d74",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, approve",
  }).then(async (result) => {
    if (!result.isConfirmed) return;
    try {
      await adminPostAction("approve", id);
      Swal.fire("Approved!", "License request approved.", "success").then(() =>
        location.reload(),
      );
    } catch (err) {
      Swal.fire("Error", err.message || "Failed to approve", "error");
    }
  });
}

function adminRejectLicense(id) {
  Swal.fire({
    title: "Reject license?",
    text: "This will reject the selected license request.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#296d74",
    confirmButtonText: "Yes, reject",
  }).then(async (result) => {
    if (!result.isConfirmed) return;
    try {
      await adminPostAction("reject", id);
      Swal.fire("Rejected!", "License request rejected.", "info").then(() =>
        location.reload(),
      );
    } catch (err) {
      Swal.fire("Error", err.message || "Failed to reject", "error");
    }
  });
}

function adminDeleteLicense(id) {
  Swal.fire({
    title: "Delete license?",
    text: "Do you want to permanently delete this license?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#296d74",
    confirmButtonText: "Yes, delete",
  }).then(async (result) => {
    if (!result.isConfirmed) return;
    try {
      await adminPostAction("delete", id);
      Swal.fire("Deleted!", "License deleted successfully.", "success").then(
        () => location.reload(),
      );
    } catch (err) {
      Swal.fire("Error", err.message || "Failed to delete", "error");
    }
  });
}
