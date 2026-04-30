let users = [
  {
    firstName: "Admin",
    lastName: "Admin",
    email: "admin@example.com",
    role: "ip"
  },
  {
    firstName: "hoba",
    lastName: "",
    email: "admin@example.com",
    role: "ip"
  }
];
let currentUserIndex = null; // To track which user is being edited

function addUser() {
  let firstName = document.getElementById("first_name").value;
  let lastName = document.getElementById("last_name").value;
  let email = document.getElementById("email").value;
  let role = document.getElementById("role").value;

  if (!firstName || !lastName || !email || !role) {
    Swal.fire({
      icon: 'error',
      title: 'Validation Error',
      text: 'Please fill in all required fields correctly.',
    });
    return;
  }

  let user = {
    firstName: firstName,
    lastName: lastName,
    email: email,
    role: role
  };

  users.push(user);
  Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: 'Account created successfully.',
    showConfirmButton: false,
    timer: 1500
  });
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
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#296d74',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      users.splice(index, 1);
      renderUsers();
      Swal.fire(
        'Deleted!',
        'The user has been deleted.',
        'success'
      )
    }
  })
}

function editUser(index) {
  currentUserIndex = index;
  const user = users[index];

  // Populate the modal with user data
  document.getElementById("edit_first_name").value = user.firstName;
  document.getElementById("edit_last_name").value = user.lastName;
  document.getElementById("edit_email").value = user.email;
  document.getElementById("edit_role").value = user.role;

  // Show the modal and overlay
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

// Initial render of users on page load
document.addEventListener('DOMContentLoaded', renderUsers);