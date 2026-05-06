function toggleSidebar() {
  document.querySelector(".container").classList.toggle("closed");
}

const API_URL = "../../src/licensing.php";

document.addEventListener("DOMContentLoaded", () => {
  const tableContainer = document.getElementById("licensing-table");
  const alertsList = document.getElementById("alerts-list");

  const addModal = document.getElementById("add-license-modal");
  const editModal = document.getElementById("edit-license-modal");
  const viewModal = document.getElementById("view-license-modal");

  const addForm = document.getElementById("add-license-form");
  const editForm = document.getElementById("edit-license-form");

  const addBtn = document.getElementById("add-license-btn");
  const closeButtons = document.querySelectorAll(".close-modal");

  const totalLicensesCount = document.getElementById("total-licenses-count");
  const activeLicensesCount = document.getElementById("active-licenses-count");
  const expiringLicensesCount = document.getElementById(
    "expiring-licenses-count",
  );
  const totalRevenue = document.getElementById("total-revenue");

  let licenses = [];

  function toggleRevenueFields(formElement, revenueModel) {
    const netSalesGroup = formElement.querySelector(".net-sales-group");
    const unitsSoldGroup = formElement.querySelector(".units-sold-group");
    const normalized = (revenueModel || "").toLowerCase();

    if (netSalesGroup) {
      netSalesGroup.classList.toggle("hidden", normalized !== "percentage");
    }
    if (unitsSoldGroup) {
      unitsSoldGroup.classList.toggle("hidden", normalized !== "per unit");
    }
  }

  function initRevenueToggle(selectId, formId) {
    const select = document.getElementById(selectId);
    const form = document.getElementById(formId);
    if (!select || !form) return;
    select.addEventListener("change", () =>
      toggleRevenueFields(form, select.value),
    );
    toggleRevenueFields(form, select.value);
  }

  function openModal(modal) {
    modal.classList.add("active");
  }

  function closeAllModals() {
    [addModal, editModal, viewModal].forEach((modal) =>
      modal.classList.remove("active"),
    );
  }

  function getStatusClass(status) {
    const normalized = (status || "").toLowerCase();
    if (normalized === "active") return "status-active";
    if (normalized === "expired") return "status-expired";
    return "status-terminated";
  }

  function showTableLoading() {
    tableContainer.innerHTML =
      '<div class="loading-state"><i class="fas fa-spinner fa-spin"></i> Loading licenses...</div>';
  }

  function showTableError(message) {
    tableContainer.innerHTML = `<div class="error-state">${message}</div>`;
  }

  function renderSummary(summary) {
    totalLicensesCount.textContent = summary.totalLicenses ?? 0;
    activeLicensesCount.textContent = summary.activeLicenses ?? 0;
    expiringLicensesCount.textContent = summary.expiringSoonLicenses ?? 0;
    totalRevenue.textContent = `$${Number(summary.totalRevenue ?? 0).toFixed(2)}`;
  }

  function renderAlerts(alerts) {
    if (!alerts || alerts.length === 0) {
      alertsList.innerHTML = '<p class="empty-state">No alerts right now.</p>';
      return;
    }

    alertsList.innerHTML = alerts
      .map(
        (alert) => `
            <div class="alert ${alert.type}">
                <i class="fas ${alert.type === "error" ? "fa-circle-xmark" : "fa-triangle-exclamation"}"></i>
                <span>${alert.message}</span>
            </div>
        `,
      )
      .join("");
  }

  function renderTable() {
    if (!licenses.length) {
      tableContainer.innerHTML =
        '<div class="empty-state">No licenses found. Add your first one.</div>';
      return;
    }

    const rows = licenses
      .map(
        (license) => `
            <tr data-id="${license.id}">
                <td><strong>${license.patent_number}</strong><small>#${license.id}</small></td>
                <td>${license.company}</td>
                <td>${license.license_type}</td>
                <td><span class="${getStatusClass(license.status)}">${license.status}</span></td>
                <td>${license.territory}</td>
                <td>${license.revenue_model}</td>
                <td>${license.revenue_value}</td>
                <td>${license.amount || "0.00"}</td>
                <td>${license.end_date}</td>
                <td>${license.termination_status || "Healthy"}</td>
                <td class="actions">
                    <i class="fas fa-eye action-view" title="View"></i>
                    <i class="fas fa-edit action-edit" title="Edit"></i>
                    <i class="fas fa-trash action-delete" title="Delete"></i>
                </td>
            </tr>
        `,
      )
      .join("");

    tableContainer.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Patent</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Territory</th>
                        <th>Revenue Model</th>
                        <th>Rate/Value</th>
                        <th>Amount</th>
                        <th>End Date</th>
                        <th>Termination</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        `;
  }

  async function fetchLicenses() {
    showTableLoading();
    try {
      const response = await fetch(`${API_URL}?action=list`);
      const payload = await response.json();

      if (!response.ok || !payload.success) {
        throw new Error(payload.message || "Unable to load licenses");
      }

      licenses = payload.data || [];
      renderTable();
      renderSummary(payload.summary || {});
      renderAlerts(payload.alerts || []);
    } catch (error) {
      showTableError(error.message || "Failed to load data");
    }
  }

  async function postAction(action, data) {
    const formData = new FormData();
    formData.append("action", action);
    Object.entries(data).forEach(([key, value]) => formData.append(key, value));

    const response = await fetch(API_URL, {
      method: "POST",
      body: formData,
    });

    const payload = await response.json();
    if (!response.ok || !payload.success) {
      throw new Error(payload.message || "Request failed");
    }
  }

  initRevenueToggle("license-revenue-model", "add-license-form");
  initRevenueToggle("edit-license-revenue-model", "edit-license-form");

  addBtn.addEventListener("click", () => openModal(addModal));

  closeButtons.forEach((btn) => btn.addEventListener("click", closeAllModals));
  [addModal, editModal, viewModal].forEach((modal) => {
    modal.addEventListener("click", (event) => {
      if (event.target === modal) {
        closeAllModals();
      }
    });
  });

  addForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    try {
      await postAction("create", {
        patent_number: document.getElementById("license-patent").value.trim(),
        company: document.getElementById("license-company").value.trim(),
        license_type: document.getElementById("license-type").value,
        territory: document.getElementById("license-territory").value.trim(),
        revenue_model: document.getElementById("license-revenue-model").value,
        revenue_value: document
          .getElementById("license-revenue-value")
          .value.trim(),
        net_sales: document.getElementById("license-net-sales").value.trim(),
        units_sold: document.getElementById("license-units-sold").value.trim(),
        min_net_sales_clause: document
          .getElementById("license-min-net-sales-clause")
          .value.trim(),
        end_date: document.getElementById("license-end-date").value,
      });

      addForm.reset();
      closeAllModals();
      await fetchLicenses();
    } catch (error) {
      alert(error.message || "Failed to create license");
    }
  });

  editForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    try {
      await postAction("update", {
        id: document.getElementById("edit-license-id").value,
        patent_number: document
          .getElementById("edit-license-patent")
          .value.trim(),
        company: document.getElementById("edit-license-company").value.trim(),
        license_type: document.getElementById("edit-license-type").value,
        status: document.getElementById("edit-license-status").value,
        territory: document
          .getElementById("edit-license-territory")
          .value.trim(),
        revenue_model: document.getElementById("edit-license-revenue-model")
          .value,
        revenue_value: document
          .getElementById("edit-license-revenue-value")
          .value.trim(),
        net_sales: document
          .getElementById("edit-license-net-sales")
          .value.trim(),
        units_sold: document
          .getElementById("edit-license-units-sold")
          .value.trim(),
        min_net_sales_clause: document
          .getElementById("edit-license-min-net-sales-clause")
          .value.trim(),
        end_date: document.getElementById("edit-license-end-date").value,
      });

      closeAllModals();
      await fetchLicenses();
    } catch (error) {
      alert(error.message || "Failed to update license");
    }
  });

  tableContainer.addEventListener("click", async (event) => {
    const row = event.target.closest("tr");
    if (!row) return;

    const id = Number(row.dataset.id);
    const current = licenses.find((item) => Number(item.id) === id);
    if (!current) return;

    if (event.target.classList.contains("action-view")) {
      document.getElementById("view-license-details").innerHTML = `
                <div class="detail-item"><strong>Patent:</strong><span>${current.patent_number}</span></div>
                <div class="detail-item"><strong>Company:</strong><span>${current.company || "-"}</span></div>
                <div class="detail-item"><strong>Type:</strong><span>${current.license_type}</span></div>
                <div class="detail-item"><strong>Status:</strong><span class="${getStatusClass(current.status)}">${current.status}</span></div>
                <div class="detail-item"><strong>Territory:</strong><span>${current.territory}</span></div>
                <div class="detail-item"><strong>Revenue:</strong><span>${current.revenue_model} - ${current.revenue_value}</span></div>
                <div class="detail-item"><strong>Calculated Amount:</strong><span>${current.amount || "0.00"}</span></div>
                <div class="detail-item"><strong>Net Sales:</strong><span>${current.net_sales || "-"}</span></div>
                <div class="detail-item"><strong>Units Sold:</strong><span>${current.units_sold || "-"}</span></div>
                <div class="detail-item"><strong>Min Net Sales Clause:</strong><span>${current.min_net_sales_clause || "-"}</span></div>
                <div class="detail-item"><strong>Termination:</strong><span>${current.termination_status || "Healthy"}</span></div>
                <div class="detail-item"><strong>Termination Reason:</strong><span>${current.termination_reason || "-"}</span></div>
                <div class="detail-item"><strong>End Date:</strong><span>${current.end_date}</span></div>
            `;
      openModal(viewModal);
      return;
    }

    if (event.target.classList.contains("action-edit")) {
      document.getElementById("edit-license-id").value = current.id;
      document.getElementById("edit-license-patent").value =
        current.patent_number;
      document.getElementById("edit-license-company").value = current.company;
      document.getElementById("edit-license-type").value = current.license_type;
      document.getElementById("edit-license-status").value = current.status;
      document.getElementById("edit-license-territory").value =
        current.territory;
      document.getElementById("edit-license-revenue-model").value =
        current.revenue_model;
      document.getElementById("edit-license-revenue-value").value =
        current.revenue_value;
      document.getElementById("edit-license-net-sales").value =
        current.net_sales || "";
      document.getElementById("edit-license-units-sold").value =
        current.units_sold || "";
      document.getElementById("edit-license-min-net-sales-clause").value =
        current.min_net_sales_clause || "";
      document.getElementById("edit-license-end-date").value = current.end_date;
      openModal(editModal);
      return;
    }

    if (event.target.classList.contains("action-delete")) {
      const confirmed = confirm(`Delete license #${current.id}?`);
      if (!confirmed) return;

      try {
        await postAction("delete", { id: current.id });
        await fetchLicenses();
      } catch (error) {
        alert(error.message || "Failed to delete license");
      }
    }
  });

  fetchLicenses();
});
