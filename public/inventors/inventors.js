// inventions.js
 function toggleSidebar() {
    document.querySelector('.container').classList.toggle('closed');
  }

// بيانات الاختراعات الأولية (إذا لم يكن هناك شيء في localStorage)
const initialInventions = [
    {
        id: "INV-2024-001",
        title: "AI-based Monitoring System",
        status: "Finalized",
        disclosureDate: "Apr 23, 2025",
        lastUpdated: "Apr 23, 2025",
        inventors: "John Doe, Jane Smith",
        field: "Artificial Intelligence"
    },
    {
        id: "INV-2024-002",
        title: "Smart Packaging Device",
        status: "Granted",
        disclosureDate: "Apr 02, 2025",
        lastUpdated: "Apr 20, 2025",
        inventors: "Jane Smith",
        field: "Packaging Technology"
    },
    {
        id: "INV-2024-003",
        title: "Solar Panel Cooling System",
        status: "Submitted",
        disclosureDate: "Apr 18, 2025",
        lastUpdated: "Apr 19, 2025",
        inventors: "Michael Brown",
        field: "Renewable Energy"
    },
    {
        id: "INV-2024-004",
        title: "Water Purification Device",
        status: "Draft",
        disclosureDate: "Apr 13, 2025",
        lastUpdated: "Apr 11, 2025",
        inventors: "Sarah Johnson",
        field: "Environmental Tech"
    },
    {
        id: "INV-2024-006",
        title: "Smart Traffic Management",
        status: "Abandoned",
        disclosureDate: "Mar 15, 2025",
        lastUpdated: "Apr 01, 2025",
        inventors: "Ahmed Hassan",
        field: "Transportation"
    }
];

// تحميل البيانات من localStorage أو استخدام البيانات الأولية
let inventionsData = JSON.parse(localStorage.getItem('inventions')) || initialInventions;

function saveInventions() {
    localStorage.setItem('inventions', JSON.stringify(inventionsData));
}
function getStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'draft':
            return 'status-draft';
        case 'finalized':
            return 'status-finalized';
        case 'submitted':
            return 'status-submitted';
        case 'granted':
            return 'status-granted';
        case 'abandoned':
            return 'status-abandoned';
        default:
            return 'status-draft';
    }
}

function generateInventionsTable() {
    const tableContainer = document.getElementById('inventions-table');
    
    let html = `
        <table>
            <thead>
                <tr>
                    <th>TITLE</th>
                    <th>STATUS</th>
                    <th>DISCLOSURE DATE</th>
                    <th>LAST UPDATED</th>
                    <th>INVENTORS</th>
                    <th>FIELD</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
    `;

    inventionsData.forEach(invention => {
        const statusClass = getStatusClass(invention.status);
        
        html += `
            <tr data-id="${invention.id}">
                <td>
                    <strong>${invention.title}</strong><br>
                    <small>${invention.id}</small>
                </td>
                <td style="text-align: center;">
                    <span class="status-badge ${statusClass}">${invention.status}</span>
                </td>
                <td>${invention.disclosureDate}</td>
                <td>${invention.lastUpdated}</td>
                <td>${invention.inventors}</td>
                <td>${invention.field}</td>
                <td class="actions">
                    <i class="fas fa-eye" title="View Details"></i>
                    <i class="fas fa-edit" title="Edit"></i>
                    <i class="fas fa-trash" title="Delete"></i>
                </td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    tableContainer.innerHTML = html;
}

// تهيئة الصفحة عند التحميل
document.addEventListener('DOMContentLoaded', () => {
    generateInventionsTable();

    // عناصر DOM
    const addInventionBtn = document.querySelector('.right .btn-primary');
    const tableContainer = document.getElementById('inventions-table');

    // نوافذ Modal
    const addModal = document.getElementById('add-invention-modal');
    const editModal = document.getElementById('edit-invention-modal');
    const viewModal = document.getElementById('view-invention-modal');
    
    // نماذج
    const addInventionForm = document.getElementById('add-invention-form');
    const editInventionForm = document.getElementById('edit-invention-form');

    // دالة لفتح نافذة
    function openModal(modal) {
        modal.classList.add('active');
    }

    // دالة لإغلاق جميع النوافذ
    function closeModal() {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }

    // فتح نافذة الإضافة
    addInventionBtn.addEventListener('click', () => openModal(addModal));

    // إغلاق النوافذ
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    });

    // إضافة اختراع جديد
    addInventionForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const newInvention = {
            id: `INV-2024-${String(inventionsData.length + 1).padStart(3, '0')}`,
            title: document.getElementById('inv-title').value,
            status: document.getElementById('inv-status').value,
            disclosureDate: new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }),
            lastUpdated: new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }),
            inventors: document.getElementById('inv-inventors').value,
            field: document.getElementById('inv-field').value
        };
        inventionsData.unshift(newInvention);
        saveInventions();
        generateInventionsTable();
        closeModal();
        addInventionForm.reset();
    });

    // تعديل اختراع
    editInventionForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const inventionId = document.getElementById('edit-inv-id').value;
        const inventionIndex = inventionsData.findIndex(inv => inv.id === inventionId);

        if (inventionIndex > -1) {
            inventionsData[inventionIndex] = {
                ...inventionsData[inventionIndex],
                title: document.getElementById('edit-inv-title').value,
                status: document.getElementById('edit-inv-status').value,
                inventors: document.getElementById('edit-inv-inventors').value,
                field: document.getElementById('edit-inv-field').value,
                lastUpdated: new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })
            };
            saveInventions();
            generateInventionsTable();
            closeModal();
        }
    });

    // تفعيل أيقونات الإجراءات في الجدول
    tableContainer.addEventListener('click', (e) => {
        const target = e.target;
        const row = target.closest('tr');
        if (!row) return;

        const inventionId = row.dataset.id;
        const invention = inventionsData.find(inv => inv.id === inventionId);
        if (!invention) return;

        // حذف
        if (target.classList.contains('fa-trash')) {
            if (confirm(`Are you sure you want to delete invention ${inventionId}?`)) {
                inventionsData = inventionsData.filter(inv => inv.id !== inventionId);
                saveInventions();
                generateInventionsTable();
            }
        }

        // تعديل
        if (target.classList.contains('fa-edit')) {
            document.getElementById('edit-inv-id').value = invention.id;
            document.getElementById('edit-inv-title').value = invention.title;
            document.getElementById('edit-inv-status').value = invention.status;
            document.getElementById('edit-inv-inventors').value = invention.inventors;
            document.getElementById('edit-inv-field').value = invention.field;
            openModal(editModal);
        }

        // عرض
        if (target.classList.contains('fa-eye')) {
            const detailsContainer = document.getElementById('view-invention-details');
            detailsContainer.innerHTML = `
                <div class="detail-item"><strong>ID:</strong> <span>${invention.id}</span></div>
                <div class="detail-item"><strong>Title:</strong> <span>${invention.title}</span></div>
                <div class="detail-item"><strong>Status:</strong> <span class="status-badge ${getStatusClass(invention.status)}">${invention.status}</span></div>
                <div class="detail-item"><strong>Disclosure Date:</strong> <span>${invention.disclosureDate}</span></div>
                <div class="detail-item"><strong>Last Updated:</strong> <span>${invention.lastUpdated}</span></div>
                <div class="detail-item"><strong>Inventors:</strong> <span>${invention.inventors}</span></div>
                <div class="detail-item"><strong>Field:</strong> <span>${invention.field}</span></div>
            `;
            openModal(viewModal);
        }
    });
});