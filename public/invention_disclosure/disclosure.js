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


// ================= Contributors Dynamic =================
function addContributor() {
  const container = document.getElementById("contributors-list");

  const row = document.createElement("div");
  row.className = "contributor-row";
  row.style.display = "flex";
  row.style.gap = "10px";
  row.style.marginBottom = "10px";

  row.innerHTML = `
    <input type="text" name="ContributorIDs[]" placeholder="Contributor ID" style="flex:2; padding:5px;">
    <input type="text" name="contributionPercentages[]" placeholder="%" style="flex:1; padding:5px;">
    <button type="button" onclick="removeRow(this)" 
        style="background:#ff4d4d;color:white;border:none;padding:5px 10px;cursor:pointer;">
        -
    </button>
  `;

  container.appendChild(row);
}

function removeRow(btn) {
  btn.parentElement.remove();
}

// expose functions globally
window.addContributor = addContributor;
window.removeRow = removeRow;

const fileInput = document.getElementById("fileInput");
const uploadText = document.getElementById("uploadText");
const uploadBox = document.getElementById("uploadBox");

fileInput.addEventListener("change", function () {
    if (this.files.length > 0) {
        uploadBox.classList.add("active");

        if (this.files.length === 1) {
            uploadText.textContent = this.files[0].name;
        } else {
            uploadText.textContent = this.files[0].name + " +" + (this.files.length - 1) + " more";
        }
    } else {
        uploadBox.classList.remove("active");
        uploadText.textContent = "Upload Files";
    }
});