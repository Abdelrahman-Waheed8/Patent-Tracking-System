document.addEventListener('DOMContentLoaded', () => {
    const initialRenewals = [
        { id: 1, patentNumber: 'US10234567', title: 'Solar Panel Efficiency', dueDate: '2026-07-15', amount: 500.00, status: 'Safe' },
        { id: 2, patentNumber: 'EP9876543', title: 'Smart Grid Communication', dueDate: '2026-05-25', amount: 1200.00, status: 'Due Soon' },
        { id: 3, patentNumber: 'JP5544332', title: 'Wireless Power Transfer', dueDate: '2026-05-05', amount: 850.00, status: 'Danger' },
        { id: 4, patentNumber: 'US1122334', title: 'Battery Cooling System', dueDate: '2026-04-10', amount: 450.00, status: 'Overdue' },
        { id: 5, patentNumber: 'CN4455667', title: 'Quantum Computing Chip', dueDate: '2026-06-10', amount: 2000.00, status: 'Paid' }
    ];

    let renewals = initialRenewals;
    localStorage.setItem('patent_renewals', JSON.stringify(renewals));
    let filteredRenewals = renewals;
    let currentPaymentId = null;

    const renewalsTable = document.getElementById('renewals-table');
    const renewalModal = document.getElementById('renewal-modal');
    const paymentModal = document.getElementById('payment-modal');
    const renewalForm = document.getElementById('renewal-form');
    const searchInput = document.getElementById('search-input');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    const alertContainer = document.getElementById('alert-container');
    const confirmPayBtn = document.getElementById('confirm-pay-btn');

    window.toggleSidebar = () => {
        document.querySelector('.container').classList.toggle('closed');
    };

    const showAlert = (type, title, message) => {
        alertContainer.innerHTML = '';
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        
        const iconMap = {
            success: 'fas fa-check-circle',
            warning: 'fas fa-exclamation-circle',
            danger: 'fas fa-times-circle',
            info: 'fas fa-info-circle'
        };

        alert.innerHTML = `
            <i class="alert-icon ${iconMap[type]}"></i>
            <div class="alert-content">
                <div class="alert-title">${title}</div>
                <div class="alert-message">${message}</div>
            </div>
            <button class="alert-close-btn">OK</button>
        `;

        alertContainer.appendChild(alert);
        alertContainer.classList.add('active');

        alert.querySelector('.alert-close-btn').onclick = () => {
            alertContainer.classList.remove('active');
        };

        alertContainer.onclick = (e) => {
            if (e.target === alertContainer) {
                alertContainer.classList.remove('active');
            }
        };
    };

    const getStatusClass = (status) => {
        switch (status.toLowerCase()) {
            case 'safe': return 'status-safe';
            case 'due soon': return 'status-due-soon';
            case 'danger': return 'status-danger';
            case 'paid': return 'status-paid';
            case 'overdue': return 'status-overdue';
            default: return '';
        }
    };

    const getDaysLeft = (dueDate) => {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const due = new Date(dueDate);
        due.setHours(0, 0, 0, 0);
        const diffTime = due - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    };

    const getStatusButtonInfo = (daysLeft) => {
        if (daysLeft > 30) {
            return { class: 'safe', icon: 'fas fa-check-circle', text: 'Safe' };
        } else if (daysLeft > 1) {
            return { class: 'warning', icon: 'fas fa-exclamation-circle', text: `Warning: ${daysLeft} days left` };
        } else if (daysLeft <= 1 && daysLeft > 0) {
            return { class: 'danger', icon: 'fas fa-exclamation-triangle', text: 'Urgent: 1 day left' };
        } else {
            return { class: 'danger', icon: 'fas fa-times-circle', text: 'Overdue' };
        }
    };

    const updateStats = () => {
        const total = renewals.length;
        const dueSoon = renewals.filter(r => {
            const daysLeft = getDaysLeft(r.dueDate);
            return daysLeft > 0 && daysLeft <= 30;
        }).length;
        const overdue = renewals.filter(r => r.status === 'Overdue').length;

        document.getElementById('total-patents').innerText = total;
        document.getElementById('due-soon').innerText = dueSoon;
        document.getElementById('overdue').innerText = overdue;
    };

    searchInput.addEventListener('keyup', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        filteredRenewals = renewals.filter(r =>
            r.patentNumber.toLowerCase().includes(searchTerm) ||
            r.title.toLowerCase().includes(searchTerm)
        );
        renderTable();
    });

    const renderTable = () => {
        if (filteredRenewals.length === 0) {
            renewalsTable.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>No renewals found.</p>
                </div>
            `;
            return;
        }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patent</th>
                        <th>Renewal</th>
                        <th>Days Left</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        filteredRenewals.forEach((renewal, index) => {
            const daysLeft = getDaysLeft(renewal.dueDate);
            const statusInfo = getStatusButtonInfo(daysLeft);
            
            if (renewal.status !== 'Paid') {
                if (daysLeft > 50) {
                    renewal.status = 'Safe';
                } else if (daysLeft >= 10) {
                    renewal.status = 'Due Soon';
                } else if (daysLeft > 0) {
                    renewal.status = 'Danger';
                } else {
                    renewal.status = 'Overdue';
                }
            }

            const isPayDisabled = renewal.status === 'Paid' || renewal.status === 'Overdue';
            
            html += `
                <tr>
                    <td><strong>${index + 1}</strong></td>
                    <td>
                        <div style="font-weight: 600;">${renewal.patentNumber}</div>
                        <div style="font-size: 12px; color: #666;">${renewal.title}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #296d74;">${renewal.dueDate}</div>
                    </td>
                    <td><strong>${daysLeft > 0 ? daysLeft + ' days' : '<span style="color:red">Overdue</span>'}</strong></td>
                    <td><span class="status-badge ${getStatusClass(renewal.status)}">${renewal.status}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-pay ${renewal.status === 'Paid' ? 'paid' : ''}" onclick="openPaymentModal(${renewal.id})" ${isPayDisabled ? 'disabled' : ''}>
                                ${renewal.status === 'Paid' ? 'Paid' : 'Pay'}
                            </button>
                            <button class="status-btn ${statusInfo.class}" onclick="showStatusAlert(${renewal.id})">
                                $
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        renewalsTable.innerHTML = html;
    };

    const saveRenewals = () => {
        localStorage.setItem('patent_renewals', JSON.stringify(renewals));
        filteredRenewals = renewals;
        updateStats();
        renderTable();
    };

    window.openPaymentModal = (id) => {
        const renewal = renewals.find(r => r.id === id);
        if (renewal) {
            currentPaymentId = id;
            document.getElementById('pay-patent-number').innerText = renewal.patentNumber;
            document.getElementById('pay-patent-title').innerText = renewal.title;
            document.getElementById('pay-due-date').innerText = renewal.dueDate;
            document.getElementById('pay-amount').innerText = `$${parseFloat(renewal.amount).toFixed(2)}`;
            document.getElementById('pay-status').innerHTML = `<span class="status-badge ${getStatusClass(renewal.status)}">${renewal.status}</span>`;
            paymentModal.classList.add('active');
        }
    };

    window.showStatusAlert = (id) => {
        const renewal = renewals.find(r => r.id === id);
        if (renewal) {
            const daysLeft = getDaysLeft(renewal.dueDate);
            
            if (renewal.status === 'Paid') {
                showAlert('success', 'Paid', `This patent (${renewal.patentNumber}) has been successfully paid.`);
                return;
            }

            if (daysLeft <= 0) {
                showAlert('danger', 'Payment Expired', `The payment period for patent ${renewal.patentNumber} has expired. Please pay immediately.`);
            } else if (daysLeft < 10) {
                showAlert('danger', 'URGENT ACTION REQUIRED', `CRITICAL: Only ${daysLeft} days left! Your patent ${renewal.patentNumber} is at high risk of expiring. Please process the payment immediately to avoid loss of rights.`);
            } else if (daysLeft <= 50) {
                showAlert('warning', 'Due Soon', `Notice: ${daysLeft} days left until the due date for patent ${renewal.patentNumber}. Please plan your payment accordingly.`);
            } else {
                showAlert('success', 'Safe Status', `Status is secure. ${daysLeft} days remaining until the due date for patent ${renewal.patentNumber}.`);
            }
        }
    };

    confirmPayBtn.addEventListener('click', () => {
        if (currentPaymentId) {
            const index = renewals.findIndex(r => r.id === currentPaymentId);
            if (index !== -1) {
                const renewal = renewals[index];
                
                if (renewal.status === 'Paid') {
                    showAlert('info', 'Attention', 'This patent is already paid.');
                    paymentModal.classList.remove('active');
                    return;
                }

                renewal.status = 'Paid';
                saveRenewals();
                showAlert('success', 'Success', 'Payment confirmed successfully.');
                paymentModal.classList.remove('active');
            }
        }
    });

    closeModalBtns.forEach(btn => {
        btn.onclick = () => {
            renewalModal.classList.remove('active');
            paymentModal.classList.remove('active');
        };
    });

    updateStats();
    renderTable();
});
