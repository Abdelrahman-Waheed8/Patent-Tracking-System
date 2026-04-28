// licensing.js
function toggleSidebar() {
    document.querySelector('.container').classList.toggle('closed');
}

// Initial license data (if nothing in localStorage)
const initialLicenses = [
    {
        id: "L-2024-001",
        patent: "US12345B2",
        company: "Samsung",
        type: "Exclusive",
        territory: "USA",
        revenueModel: "Percentage",
        revenueValue: "5%",
        status: "Active",
        startDate: "2024-01-15",
        endDate: "2029-01-14"
    },
    {
        id: "L-2024-002",
        patent: "EP67890A1",
        company: "LG",
        type: "Non-Exclusive",
        territory: "Europe",
        revenueModel: "Per Unit",
        revenueValue: "$10",
        status: "Active",
        startDate: "2023-06-01",
        endDate: "2025-05-31"
    },
    {
        id: "L-2023-003",
        patent: "JP2022-54321",
        company: "Sony",
        type: "Non-Exclusive",
        territory: "Global",
        revenueModel: "Fixed",
        revenueValue: "$50,000/year",
        status: "Expired",
        startDate: "2022-03-01",
        endDate: "2024-02-29"
    },
     {
        id: "L-2024-004",
        patent: "US98765B2",
        company: "Apple Inc.",
        type: "Exclusive",
        territory: "Global",
        revenueModel: "Percentage",
        revenueValue: "3%",
        status: "Active",
        startDate: "2024-02-20",
        endDate: "2026-07-15"
    },
    {
        id: "L-2022-005",
        patent: "CN1098765A",
        company: "Huawei",
        type: "Terminated",
        territory: "China",
        revenueModel: "Fixed",
        revenueValue: "$200,000",
        status: "Terminated",
        startDate: "2022-01-01",
        endDate: "2027-12-31"
    }
];

// Load data from localStorage or use initial data
let licensesData = JSON.parse(localStorage.getItem('licenses')) || initialLicenses;

function saveLicenses() {
    localStorage.setItem('licenses', JSON.stringify(licensesData));
}

function getStatusClass(status) {
    switch (status.toLowerCase()) {
        case 'active':
            return 'status-active';
        case 'expired':
            return 'status-expired';
        case 'terminated':
            return 'status-terminated';
        default:
            return '';
    }
}

function formatRevenue(model, value) {
    if (model === 'Percentage') return `${value}`;
    if (model === 'Per Unit') return `${value} / unit`;
    if (model === 'Fixed') return `${value}`;
    return value;
}

function generateLicensingTable() {
    const tableContainer = document.getElementById('licensing-table');
    
    let html = `
        <table>
            <thead>
                <tr>
                    <th>License ID</th>
                    <th>Patent</th>
                    <th>Company</th>
                    <th>Type</th>
                    <th>Territory</th>
                    <th>Revenue</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (licensesData.length === 0) {
        html += `<tr><td colspan="8" style="text-align: center; padding: 20px;">No licenses found.</td></tr>`;
    } else {
        licensesData.forEach(license => {
            const statusClass = getStatusClass(license.status);
            
            html += `
                <tr data-id="${license.id}">
                    <td><strong>${license.id}</strong></td>
                    <td>${license.patent}</td>
                    <td>${license.company}</td>
                    <td>${license.type}</td>
                    <td>${license.territory}</td>
                    <td>${formatRevenue(license.revenueModel, license.revenueValue)}</td>
                    <td>
                        <span class="status-badge ${statusClass}">${license.status}</span>
                    </td>
                    <td class="actions">
                        <i class="fas fa-eye" title="View Details"></i>
                        <i class="fas fa-edit" title="Edit"></i>
                        <i class="fas fa-trash" title="Delete"></i>
                    </td>
                </tr>
            `;
        });
    }

    html += `
            </tbody>
        </table>
    `;

    tableContainer.innerHTML = html;
}

function updateSummaryCards() {
    const totalLicenses = licensesData.length;
    const activeLicenses = licensesData.filter(l => l.status === 'Active').length;
    
    // Calculate expiring soon (e.g., within 90 days)
    const now = new Date();
    const ninetyDaysFromNow = new Date(now.setDate(now.getDate() + 90));
    const expiringSoon = licensesData.filter(l => 
        l.status === 'Active' && new Date(l.endDate) <= ninetyDaysFromNow
    ).length;

    // Dummy revenue calculation
    const totalRevenue = licensesData.reduce((acc, l) => {
        if (l.status === 'Active' && l.revenueModel === 'Fixed') {
            // A simple parser for fixed revenue, this should be more robust in a real app
            return acc + (parseFloat(l.revenueValue.replace(/[^0-9.-]+/g,"")) || 0);
        }
        return acc;
    }, 510000); // Starting with a base for non-fixed models for demo

    document.getElementById('total-licenses-count').textContent = totalLicenses;
    document.getElementById('active-licenses-count').textContent = activeLicenses;
    document.getElementById('expiring-licenses-count').textContent = expiringSoon;
    document.getElementById('total-revenue').textContent = `${totalRevenue.toLocaleString()}`;
}

function generateAlerts() {
    const alertsList = document.getElementById('alerts-list');
    alertsList.innerHTML = ''; // Clear previous alerts

    const now = new Date();
    const thirtyDaysFromNow = new Date(new Date().setDate(now.getDate() + 30));

    licensesData.forEach(license => {
        const endDate = new Date(license.endDate);
        if (license.status === 'Active' && endDate <= thirtyDaysFromNow) {
            const daysDiff = Math.ceil((endDate - now) / (1000 * 60 * 60 * 24));
            const alertType = daysDiff <= 10 ? 'error' : 'warning';
            const message = daysDiff > 0 
                ? `License ${license.id} for patent ${license.patent} will expire in ${daysDiff} days.`
                : `License ${license.id} for patent ${license.patent} has expired.`;

            alertsList.innerHTML += `
                <div class="alert ${alertType}">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>${message}</span>
                </div>
            `;
        }
    });

    if (alertsList.innerHTML === '') {
        alertsList.innerHTML = '<p>No critical alerts at the moment.</p>';
    }
}


// Initialize page on load
document.addEventListener('DOMContentLoaded', () => {
    generateLicensingTable();
    updateSummaryCards();
    generateAlerts();

    // DOM elements
    const addLicenseBtn = document.getElementById('add-license-btn');
    const tableContainer = document.getElementById('licensing-table');

    // Modals
    const addModal = document.getElementById('add-license-modal');
    const editModal = document.getElementById('edit-license-modal');
    const viewModal = document.getElementById('view-license-modal');
    
    // Forms
    const addLicenseForm = document.getElementById('add-license-form');
    const editLicenseForm = document.getElementById('edit-license-form');

    // Function to open a modal
    function openModal(modal) {
        modal.classList.add('active');
    }

    // Function to close all modals
    function closeModal() {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }

    // Open Add modal
    addLicenseBtn.addEventListener('click', () => openModal(addModal));

    // Close modals events
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    });

    // Add new license
    addLicenseForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const newLicense = {
            id: `L-2024-${String(licensesData.length + 1).padStart(3, '0')}`,
            patent: document.getElementById('license-patent').value,
            company: document.getElementById('license-company').value,
            type: document.getElementById('license-type').value,
            territory: document.getElementById('license-territory').value,
            revenueModel: document.getElementById('license-revenue-model').value,
            revenueValue: document.getElementById('license-revenue-value').value,
            status: 'Active', // New licenses are active by default
            startDate: new Date().toISOString().split('T')[0],
            endDate: document.getElementById('license-end-date').value
        };

        // Basic conflict check (Function 25 simulation)
        if (newLicense.type === 'Exclusive') {
            const conflict = licensesData.some(l => l.patent === newLicense.patent && l.status === 'Active');
            if (conflict) {
                alert(`Conflict: An active license already exists for patent ${newLicense.patent}. Cannot create an exclusive license.`);
                return;
            }
        }

        licensesData.unshift(newLicense);
        saveLicenses();
        generateLicensingTable();
        updateSummaryCards();
        generateAlerts();
        closeModal();
        addLicenseForm.reset();
    });

    // Edit license
    editLicenseForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const licenseId = document.getElementById('edit-license-id').value;
        const licenseIndex = licensesData.findIndex(l => l.id === licenseId);

        if (licenseIndex > -1) {
            licensesData[licenseIndex] = {
                ...licensesData[licenseIndex],
                patent: document.getElementById('edit-license-patent').value,
                company: document.getElementById('edit-license-company').value,
                type: document.getElementById('edit-license-type').value,
                status: document.getElementById('edit-license-status').value,
                territory: document.getElementById('edit-license-territory').value,
                revenueModel: document.getElementById('edit-license-revenue-model').value,
                revenueValue: document.getElementById('edit-license-revenue-value').value,
                endDate: document.getElementById('edit-license-end-date').value,
            };
            saveLicenses();
            generateLicensingTable();
            updateSummaryCards();
            generateAlerts();
            closeModal();
        }
    });

    // Table actions (View, Edit, Delete)
    tableContainer.addEventListener('click', (e) => {
        const target = e.target;
        const row = target.closest('tr');
        if (!row || !row.dataset.id) return;

        const licenseId = row.dataset.id;
        const license = licensesData.find(l => l.id === licenseId);
        if (!license) return;

        // Delete
        if (target.classList.contains('fa-trash')) {
            if (confirm(`Are you sure you want to delete license ${licenseId}?`)) {
                licensesData = licensesData.filter(l => l.id !== licenseId);
                saveLicenses();
                generateLicensingTable();
                updateSummaryCards();
                generateAlerts();
            }
        }

        // Edit
        if (target.classList.contains('fa-edit')) {
            document.getElementById('edit-license-id').value = license.id;
            document.getElementById('edit-license-patent').value = license.patent;
            document.getElementById('edit-license-company').value = license.company;
            document.getElementById('edit-license-type').value = license.type;
            document.getElementById('edit-license-status').value = license.status;
            document.getElementById('edit-license-territory').value = license.territory;
            document.getElementById('edit-license-revenue-model').value = license.revenueModel;
            document.getElementById('edit-license-revenue-value').value = license.revenueValue;
            document.getElementById('edit-license-end-date').value = license.endDate;
            openModal(editModal);
        }

        // View
        if (target.classList.contains('fa-eye')) {
            const detailsContainer = document.getElementById('view-license-details');
            detailsContainer.innerHTML = `
                <div class="detail-item"><strong>License ID:</strong> <span>${license.id}</span></div>
                <div class="detail-item"><strong>Patent:</strong> <span>${license.patent}</span></div>
                <div class="detail-item"><strong>Company:</strong> <span>${license.company}</span></div>
                <div class="detail-item"><strong>Status:</strong> <span class="status-badge ${getStatusClass(license.status)}">${license.status}</span></div>
                <div class="detail-item"><strong>Type:</strong> <span>${license.type}</span></div>
                <div class="detail-item"><strong>Territory:</strong> <span>${license.territory}</span></div>
                <div class="detail-item"><strong>Revenue:</strong> <span>${formatRevenue(license.revenueModel, license.revenueValue)}</span></div>
                <div class="detail-item"><strong>Start Date:</strong> <span>${new Date(license.startDate).toLocaleDateString()}</span></div>
                <div class="detail-item"><strong>End Date:</strong> <span>${new Date(license.endDate).toLocaleDateString()}</span></div>
            `;
            openModal(viewModal);
        }
    });
});