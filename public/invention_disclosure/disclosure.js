// ================= Sidebar Toggle =================
function toggleSidebar() {
  document.querySelector(".container").classList.toggle("closed");
}

window.toggleSidebar = toggleSidebar;

// ================= Active Menu =================
let links = document.querySelectorAll(".menu a");

links.forEach((link) => {
  link.addEventListener("click", function () {
    links.forEach((l) => l.classList.remove("active"));
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
  row.style.flexWrap = "wrap";

  row.innerHTML = `
    <input type="text" name="ContributorIDs[]" placeholder="Contributor ID" style="flex:2; padding:5px;">
    <input type="text" name="contributionPercentages[]" placeholder="%" style="flex:1; padding:5px;">
    <input type="text" name="companyNames[]" placeholder="External Company (optional)" style="flex:2; padding:5px;">
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

window.addContributor = addContributor;
window.removeRow = removeRow;

// ================= External Agreement Toggle =================
function applyExternalAgreementIfChecked() {
  const checked = document.getElementById("externalAgreement")?.checked;
  const rows = document.querySelectorAll(".contributor-row");

  rows.forEach((row) => {
    let existing = row.querySelector(".company-field");

    if (checked) {
      if (!existing) {
        const wrapper = document.createElement("div");
        wrapper.className = "company-field";
        wrapper.style.flexBasis = "100%";
        wrapper.style.marginTop = "5px";

        wrapper.innerHTML = `
          <input type="text" name="contributorsCompany[]" 
                 placeholder="External Company Name"
                 style="width:100%; padding:5px;">
        `;

        row.appendChild(wrapper);
      }
    } else {
      if (existing) existing.remove();
    }
  });
}

window.applyExternalAgreementIfChecked = applyExternalAgreementIfChecked;

// ================= File Upload =================
const fileInput = document.getElementById("fileInput");
const uploadText = document.getElementById("uploadText");
const uploadBox = document.getElementById("uploadBox");

if (fileInput) {
  fileInput.addEventListener("change", function () {
    if (this.files.length > 0) {
      uploadBox.classList.add("active");

      if (this.files.length === 1) {
        uploadText.textContent = this.files[0].name;
      } else {
        uploadText.textContent =
          this.files[0].name + " +" + (this.files.length - 1) + " more";
      }
    } else {
      uploadBox.classList.remove("active");
      uploadText.textContent = "Upload Files";
    }
  });
}

function toggleFields() {
  const isNational = document.getElementById("is_national").checked;
  const nationalDiv = document.getElementById("national_section");
  const internationalDiv = document.getElementById("international_section");

  if (isNational) {
    nationalDiv.style.display = "block";
    internationalDiv.style.display = "none";
  } else {
    nationalDiv.style.display = "none";
    internationalDiv.style.display = "block";
  }
}
